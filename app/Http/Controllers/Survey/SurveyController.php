<?php

namespace App\Http\Controllers\Survey;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon; 

use App\User;

class SurveyController extends Controller
{
    //
    public function index($id){
        $data['survey_id'] = $id;

        $data_survey = DB::table('surveys')
            ->select('surveys.created_by','surveys.name','survey_process.*','it_goal.PP')
            ->leftJoin('it_related_goal','it_related_goal.survey','=','surveys.id')
            ->leftJoin('survey_process',function($join){
                $join->on('survey_process.survey', '=','surveys.id')
                     ->on('survey_process.it_related_goal', '=','it_related_goal.id');
            })
            ->leftJoin('it_goal', 'it_goal.id', '=', 'it_related_goal.id')
            ->where('surveys.id',$id)
            ->get();

        if(!$data_survey->first()){
            abort(404);
        }

        $status_ownership = "";
        if ($data_survey->first()->created_by == Auth::user()->id){
            $status_ownership = "CREATOR";
        }else{
            $status_of_surveys = DB::table('survey_members')
                ->select('role')
                ->where([
                    ['survey','=',$id],
                    ['user','=',Auth::user()->id]
                ])
                ->get();
            if($status_of_surveys->first()){
                $status_ownership = strtoupper(explode("-",$status_of_surveys->first()->role)[1]);
            }else{
                abort(404);
            }
        }

        $data['status_ownership'] = $status_ownership;

        // $data['itgoalprocesses'] = DB::table('it_related_goal')
        //     ->select('it_related_goal_to_process.*','it_goal.PP')
        //     ->join('it_related_goal_to_process','it_related_goal_to_process.it_related_goal', '=','it_related_goal.id')
        //     ->join('it_goal', 'it_goal.id', '=', 'it_related_goal.id')
        //     ->where('it_related_goal.survey',$id)
        //     ->get();
        if($data_survey->first()){
            $data['surveys'] = $data_survey;
            $data['survey_name'] = $data_survey->first()->name;
            return view('survey.survey',$data);
        }else{
            abort(404);
        }
    }

    public function addQuestion(Request $request){
    	return view('survey.survey-add-question');	
    }

    public function chooseAnswer($inputans){
        $inputan = explode("-",$inputans);
        $id = $inputan[0];
        $data['survey_id'] = $id;
        $it_related_goal = $inputan[1];
        $process = $inputan[2];
        $target_level = "";
        DB::table('survey_process')
            ->where([
                ['it_related_goal','=',$it_related_goal],
                ['process','=',$process],
                ['survey','=',$id],
                ['status','=','1-Waiting']
            ])
            ->update(['status' => '2-Process Survey']);
        $d_surveys = DB::table('surveys')
                    ->select('surveys.name','surveys.created_by','survey_process.target_level')
                    ->leftJoin('survey_process','survey_process.survey','=','surveys.id')
                    ->where([
                        ['surveys.id','=',$id],
                        ['survey_process.it_related_goal','=',$it_related_goal],
                        ['survey_process.process','=',$process]
                    ])
                    ->get();

        if($d_surveys->first()){
            if ($d_surveys->first()->created_by != Auth::user()->id){
                $data['survey_members'] = DB::table('survey_members')
                    ->select('survey_members.user','users.username')
                    ->leftJoin('users','users.id','=','survey_members.user')
                    ->where([
                        ['survey','=',$id]
                    ])
                    ->get();
                $ok = 0;
                foreach($data['survey_members'] as $surmem){
                    if($surmem->user == Auth::user()->id){
                        $ok = 1;
                    }
                }
                if(!$ok){
                    abort(404);
                }
            }
            $data['survey_name'] = $d_surveys->first()->name;
            $target_level = $d_surveys->first()->target_level;
        }else{
            abort(404);
        }

        $levels = DB::table('process_attributes')
            ->select('level')
            ->groupBy('level')
            ->where('level','<=',$target_level)
            ->get();

        $datasurvey = array();
        foreach($levels as $index => $leveled){
            $level = $leveled->level;
            $dats = DB::table('surveys')
                ->select('process_attributes.purpose','surveys.name','process_outcome.*','outcomes.description')
                ->leftJoin('survey_process','survey_process.survey','=','surveys.id')
                ->leftJoin('process_outcome','process_outcome.process','=','survey_process.process')
                ->leftJoin('outcomes','outcomes.id','=','process_outcome.outcome')
                ->leftJoin('process_attributes', 'process_attributes.id', '=','outcomes.process_attribute')
                ->where([
                    ['surveys.id','=',$id],
                    ['survey_process.process','=',$process],
                    ['survey_process.it_related_goal','=',$it_related_goal],
                    ['process_attributes.level','=',$level]
                ])
                ->get();
            if($dats->first()){
                $datasurvey[$index]['surveys'] = $dats;
            }
        }
        $data['levels'] = $datasurvey;

       // SELECT a.name, c.*, d.description
       //  FROM surveys a
       //  LEFT JOIN survey_process b ON b.survey = a.id
       //  LEFT JOIN process_outcome c ON c.process = b.process
       //  LEFT JOIN outcomes d ON d.id  = c.outcome
       //  LEFT JOIN process_attributes e ON e.id = d.process_attribute
       //  WHERE a.id = 1 and b.process = 'EDM01'

    	return view('survey.survey-choose-answer', $data);	
    }

    public function postAnswer($inputans, Request $request){
        $inputan = explode("-",$inputans);
        $survey_id = $inputan[0];
        $it_related_goal = $inputan[1];
        $process = $inputan[2];

        // print_r($request->post('metcriteria'));
        foreach ($request->post('metcriteria') as $process_outcome => $answer){
            echo $process_outcome." ".$answer." ".$request->post('comment')[$process_outcome];
            DB::table('survey_process_outcomes')->insert(
                        [   'survey' => $survey_id, 
                            'it_related_goal' => $it_related_goal,
                            'process_outcome' => $process_outcome,
                            'met_criteria' => $answer,
                            'comment' => $request->post('comment')[$process_outcome],
                            'answered_by' => Auth::user()->id
                        ]
                    );
        }
        
        DB::table('survey_process')
            ->where([
                ['it_related_goal','=',$it_related_goal],
                ['process','=',$process],
                ['survey','=',$survey_id]
            ])
            ->update(['status' => '4-Done Survey']);
        return redirect('survey/'.$survey_id);
    }

    public function get_process_outcome_wp($id){
        $input = explode(",",$id);
        $survey_id = $input[1];
        // SELECT c.*, d.file FROM `process_outcome` a
        // LEFT JOIN process_outcome_wp b ON b.process_outcome = a.id
        // LEFT JOIN working_product c ON c.id = b.working_product
        // LEFT JOIN survey_working_products d ON d.working_product = c.id and d.survey = 1
        // WHERE a.id = 'EDM01-O1'
        $data = DB::table('process_outcome')
                ->select('working_product.*','survey_working_products.file')
                ->leftJoin('process_outcome_wp','process_outcome_wp.process_outcome','=','process_outcome.id')
                ->leftJoin('working_product','working_product.id','=','process_outcome_wp.working_product')
                ->leftJoin('survey_working_products',function($join) use($survey_id){
                    $join->on('survey_working_products.working_product', '=','working_product.id')
                         ->on('survey_working_products.survey', '=', DB::raw($survey_id));
                })
                ->where("process_outcome.id","=",$input[0])->get();
        
        echo json_encode([
            'draw' => 1,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data,
        ]);;
    }


    public function uploadWp($id,Request $request){

        $validator = Validator::make(
            $request->all(), [
            'files.*' => 'required|mimes:pdf,doc,docx,xls,xlsx'
            ],[
                'files.*.required' => 'Upload Files Failed, files must not empty!',
                'files.*.mimes' => 'Only pdf, word (.doc|.docx), and excel(.xls|.xlsx) files are allowed',
            ]
        );

        if ($validator->fails()) {
            return json_encode([
                'status' => 0,
                'messages' => $validator->errors()->first()
            ]);
        }

        $survey = \App\Models\Survey::where('id', $id)->first();

        if(!$survey){
            return json_encode([
                'status' => 0,
                'messages' => "Upload Files Failed, current Survey not found!"
            ]);
        }

        if(!$request->file('files')){
            return json_encode([
                'status' => 0,
                'messages' => "Upload Files Failed, files must not empty!"
            ]);
        }

        $root_path = "/storage/index/survey/";

        $root = Files::where('url',$root_path)->first();
        $root_id = "";
        if (!$root) {
            Storage::makeDirectory('survey');
            $folder = new Files();
            $folder->folder_root = 0;
            $folder->name = 'survey';
            $folder->url= $root_path;
            $folder->is_file = 0;
            $folder->created_by = Auth::user()->id;
            $folder->save();
            $root_id = $folder->id;
        }else{
            $root_id = $root->id;
        }

        $survey_path = $root_path.$survey->name.'/';
        $survey_files = Files::where('url',$survey_path)->first();
        $survey_files_id = "";
        if (!$survey_files) {
            Storage::makeDirectory('survey/'.$survey->name.'/');
            $folder = new Files();
            $folder->folder_root = $root_id;
            $folder->name = $survey->name;
            $folder->url= $survey_path;
            $folder->is_file = 0;
            $folder->created_by = Auth::user()->id;
            $folder->save();
            $survey_files_id = $folder->id;
        }else{
            $survey_files_id = $survey_files->id;
        }

        if($survey_files_id){
            foreach ($request->file('files') as $wpid => $file) {
                if ($file->isValid()) {

                    $path = $file->store('survey/'.$survey->name);

                    $files = [
                        'folder_root' => $survey_files_id,
                        'name' => $file->getClientOriginalName(),
                        'url' => "/storage/index/".$path,
                        'is_file' => 1,
                        'version' => 1,
                        'size' => $file->getClientSize(),
                        'created_by' => Auth::user()->id,
                        'created_at' => $now = Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => $now,
                    ];

                    $fileid = Files::insertGetId($files);

                    DB::table('survey_working_products')->insert(
                        [   'survey' => $id, 
                            'working_product' => $wpid,
                            'file' => $fileid
                        ]
                    );
                }
            }
        }

        return json_encode([
            'status' => 1,
            'messages' => "Upload Files Success!"
        ]);
    }

    public function viewWp(Files $file)
    {
        $exists = Storage::disk('public')->has(str_replace('/storage/index/', '', $file->url));
        if($exists){
            return Storage::response(str_replace('/storage/index/', '', $file->url));
        }else{
            return 1;
        }
    }

    public function ajax_get_list_user()
    {
        $exists = Storage::disk('public')->has(str_replace('/storage/index/', '', $file->url));
        if($exists){
            return Storage::download(str_replace('/storage/index/', '', $file->url), $file->name);
        }else{
            return 1;
        }
    }

    public function analyze($id,$inputans){
        $inputan = explode("-",$inputans);
        $id = $inputan[0];
        $data['survey_id'] = $id;
        $it_related_goal = $inputan[1];
        $process = $inputan[2];
        $target_level = "";
        DB::table('survey_process')
            ->where([
                ['it_related_goal','=',$it_related_goal],
                ['process','=',$process],
                ['survey','=',$id],
                ['status','=','4-Done Survey']
            ])
            ->update(['status' => '5-Analyze']);
        $d_surveys = DB::table('surveys')
                    ->select('surveys.name','surveys.created_by','survey_process.target_level')
                    ->leftJoin('survey_process','survey_process.survey','=','surveys.id')
                    ->where([
                        ['surveys.id','=',$id],
                        ['survey_process.it_related_goal','=',$it_related_goal],
                        ['survey_process.process','=',$process]
                    ])
                    ->get();

        if($d_surveys->first()){
            $data['survey_members'] = DB::table('survey_members')
                ->select('survey_members.user','survey_members.role','users.username')
                ->leftJoin('users','users.id','=','survey_members.user')
                ->where([
                    ['survey','=',$id]
                ])
                ->get();
            $ok = 0;
            foreach($data['survey_members'] as $surmem){
                if($surmem->user == Auth::user()->id){
                    $ok = 1;
                }
            }
            if ($d_surveys->first()->created_by != Auth::user()->id){
                if(!$ok){
                    abort(404);
                }
            }
            $data['survey_name'] = $d_surveys->first()->name;
            $target_level = $d_surveys->first()->target_level;
        }else{
            abort(404);
        }


        $levels = DB::table('process_attributes')
            ->select('level')
            ->groupBy('level')
            ->where('level','<=',$target_level)
            ->get();

        $datasurvey = array();
        foreach($levels as $index => $leveled){
            $level = $leveled->level;
            $dats = DB::table('surveys')
                ->select('process_attributes.purpose','surveys.name','process_outcome.*','outcomes.description', 'survey_process_outcomes.met_criteria','survey_process_outcomes.comment')
                ->leftJoin('survey_process','survey_process.survey','=','surveys.id')
                ->leftJoin('process_outcome','process_outcome.process','=','survey_process.process')
                ->leftJoin('outcomes','outcomes.id','=','process_outcome.outcome')
                ->leftJoin('process_attributes', 'process_attributes.id', '=','outcomes.process_attribute')
                ->leftJoin('survey_process_outcomes',function($join) use($id, $it_related_goal){
                    $join->on('survey_process_outcomes.process_outcome','=','process_outcome.id')
                         ->on('survey_process_outcomes.survey', '=',DB::raw($id))
                         ->on('survey_process_outcomes.it_related_goal', '=', DB::raw($it_related_goal));
                })
                ->where([
                    ['surveys.id','=',$id],
                    ['survey_process.process','=',$process],
                    ['survey_process.it_related_goal','=',$it_related_goal],
                    ['process_attributes.level','=',$level]
                ])
                ->get();
            if($dats->first()){
                $datasurvey[$index]['surveys'] = $dats;
            }
        }
        $data['levels'] = $datasurvey;

        return view('survey.survey-analyze', $data);  
    }

    public function analyzePost($id,$inputans, Request $request){
        $inputan = explode("-",$inputans);
        $survey_id = $inputan[0];
        $it_related_goal = $inputan[1];
        $process = $inputan[2];
        $typesubmit = $request->post('btnsubmit');
        
        $status = "";

        $percent_level = 0;
        $count_level = 0;
        $level_tercapai = 0;
        $percent_tercapai = 0;

        $tercapai = array();

        if($typesubmit == 'finish'){
            $status = '7-Done';
            foreach ($request->post('metcriteria') as $level => $process_outcomes){
                $total_process = 0;
                $total_percent = 0;
                foreach($process_outcomes as $process_outcome => $answer){
                    $total_process++;
                    $percent = 0;
                    if($request->post('acceptance')[$level][$process_outcome] == 'agree'){
                        $datas = DB::table('process_outcome')
                                ->select('working_product.*','survey_working_products.file',DB::raw('files.name as filename'),DB::raw('files.url as fileurl'),DB::raw('files.id as fileid'))
                                ->leftJoin('process_outcome_wp','process_outcome_wp.process_outcome','=','process_outcome.id')
                                ->leftJoin('working_product','working_product.id','=','process_outcome_wp.working_product')
                                ->leftJoin('survey_working_products',function($join) use($survey_id){
                                    $join->on('survey_working_products.working_product', '=','working_product.id')
                                         ->on('survey_working_products.survey', '=', DB::raw($survey_id));
                                })
                                ->leftJoin('files','files.id','=','survey_working_products.file')
                                ->where("process_outcome.id","=",$process_outcome)->get();
                        
                        $available = 0;                 
                        foreach($datas as $index => $data){
                            if($data->file){
                                $available++;
                            }
                        }
                        
                        $percent = round($available/$datas->count()*100);
                    }
                    $total_percent += $percent;
                    
                    DB::table('survey_process_outcomes')
                        ->where([
                            ['survey','=',$survey_id],
                            ['it_related_goal','=',$it_related_goal],
                            ['process_outcome','=',$process_outcome]
                        ])
                        ->update(
                            [   
                                'note' => $request->post('note')[$level][$process_outcome],
                                'acceptance' => $request->post('acceptance')[$level][$process_outcome],
                                'percent' => $percent,
                                'recomended_by' => Auth::user()->id
                            ]
                        );
                }

                $percent_level += round($total_percent/$total_process);
                $count_level++;

                if (round($total_percent/$total_process) >= 85){
                    if(!array_key_exists("finish",$tercapai)){
                        $level_tercapai = $level;
                    }
                }else{
                    $tercapai['finish'] = $level_tercapai;
                }
            }

            $percent_tercapai = round($percent_level/$count_level);
        }else{
            $status = '6-On Save Analyze';
        }

        DB::table('survey_process')
            ->where([
                ['it_related_goal','=',$it_related_goal],
                ['process','=',$process],
                ['survey','=',$survey_id]
            ])
            ->update([
                'status' => $status,
                'level' => array_key_exists("finish",$tercapai) ? $tercapai['finish'] : $level_tercapai,
                'percent' => $percent_tercapai
            ]);

        return redirect('survey/'.$survey_id);
    }

    public function ajax_get_list_user($condition)
    {
        if($condition == 'no'){
            echo json_encode(DB::table('users')->where('id','<>',Auth::user()->id)->get());
        }else{
            echo json_encode(DB::table('users')->get());
        }
    }

    public function task($id)
    {
        $data['survey_id'] = $id;
        // $task = new \App\Models\Task;
        // $data['tasks'] = $task->get();
        $priority = array();
        $priority['1-High'] = "!!!";
        $priority['2-Medium'] = "!!";
        $priority['3-Low'] = "!";
        $data['priorities'] = $priority;

        $data_survey = DB::table('surveys')
            ->select('surveys.id')
            ->where('surveys.id',$id)
            ->get();

        if($data_survey->first()){
            $data_tasks = DB::table('tasks')
                ->select('tasks.*','users.username',DB::raw('DATE_FORMAT(tasks.due_date, "%d %b %Y, %H:%i") as due_dates'))
                ->join('users','users.id','=','tasks.assign')
                ->where('tasks.survey',$id)
                ->get();
            $data['tasks'] = $data_tasks;
            return view('survey.task',$data);  
        }else{
            abort(404);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $sekolah->sekolah_id = $request->post('i-idsekolah');
        $survey = new \App\Models\Survey;
        $survey->name = $request->post('i_n_name_survey');
        $survey->expired = Carbon::createFromFormat('m/d/Y h:i A', $request->post('i_n_expire'))->format('Y-m-d H:i');
        $survey->created_by = Auth::user()->id;
        $post = $survey->save();

        if($post){
            $id = $survey->id;
            if($request->get('i_n_surveyor')){
                foreach ($request->get('i_n_surveyor') as $surveyor) {
                    $surveymembers = new \App\Models\SurveyMembers;
                    $surveymembers->user = $surveyor;
                    $surveymembers->survey = $id;
                    $surveymembers->role = "2-Responden";
                    $surveymembers->save();
                }                
            }
            if($request->get('i_n_client')){
                foreach ($request->get('i_n_client') as $surveyor) {
                    $surveymembers = new \App\Models\SurveyMembers;
                    $surveymembers->user = $surveyor;
                    $surveymembers->survey = $id;
                    $surveymembers->role = "1-Surveyor";
                    $surveymembers->save();
                }
            }
            if($request->get('i_itgoal')){
                foreach ($request->get('i_itgoal') as $itgoal){
                    $id_itgoal = DB::table('it_related_goal')->insertGetId(
                        [   'it_goal' => $itgoal, 
                            'survey' => $id
                        ]
                    );
                    if($request->get('i_itgoal_process')){
                        foreach($request->get('i_itgoal_process')[$itgoal] as $itgoalprocess){
                            // $a_itgoalprocess = explode("-",$itgoalprocess);
                            // if($a_itgoalprocess[0] == $itgoal){
                                $survey_process = DB::table('survey_process')->insertGetId(
                                    [   
                                        'it_related_goal' => $id_itgoal,
                                        'process' => $itgoalprocess,
                                        'survey' => $id,
                                        'target_level' => $request->get('i_itgoal_process_level')[$itgoal][$itgoalprocess],
                                        'target_percent' => $request->get('i_itgoal_process_percent')[$itgoal][$itgoalprocess],
                                        'status' => '1-Waiting'
                                    ]
                                );
                            // }
                        }
                    }
                }
            }
            return redirect('survey/'.$id);
        }
        // return response()->json($post);
        return redirect('survey');
    }

    public function task_store(Request $request){
        $task = new \App\Models\Task;
        $task->survey = $request->post('i_n_survey_id');
        $task->name = $request->post('i_n_name_task');
        $task->assign = $request->post('i_n_assignee');
        $task->due_date = Carbon::createFromFormat('m/d/Y h:i A', $request->post('i_n_due_date'))->format('Y-m-d H:i');
        $task->detail = $request->post('i_n_detail');
        $task->color = $request->post('i_n_color');
        $task->progress = 0;
        $task->priority = $request->post('i_n_priority');
        $task->created_by = Auth::user()->id;
        $post = $task->save();
        if($post){
            $id = $task->id;
            foreach ($request->get('i_n_participant') as $participant) {
                $taskparticipants = new \App\Models\TaskParticipants;
                $taskparticipants->task = $id;
                $taskparticipants->team_member = $participant;
                $taskparticipants->save();
            }
        }
        return redirect('survey/task/'.$request->post('i_n_survey_id'));
    }
}
