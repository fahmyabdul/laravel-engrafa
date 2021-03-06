$(document).ready(function(){
	$("#mn_create_new_team").click(function(){
		$('#modal-n-survey').modal('show');
    initialize_select_user("#i_n_surveyor");
    initialize_select_user("#i_n_client");
	});

  $("#b_create_new_team").click(function(){
    $('#modal-n-survey').modal('show');
    initialize_select_user("#i_n_surveyor");
    initialize_select_user("#i_n_client");
  });

    $('.select2').select2({
        width: '100%',
        placeholder: "Pilih Dari List",
    });
    $('#i_n_expire').datetimepicker({});

    if($("#i_n_survey_type").val() !== ""){
      var stype = $("#i_n_survey_type").val();
      if(!stype){
        $('.list-itgoal-purpose').hide();
        $('.list-itgoal-pain').hide();
      }else if(stype === '1-Purpose'){
        $('.list-itgoal-purpose').show();
        $('.list-itgoal-pain').hide();
      }else if(stype === '2-Pain'){
        $('.list-itgoal-pain').show();
        $('.list-itgoal-purpose').hide();
      }
    }else{
      $("#list-itgoal").hide();
    }

    $("#i_n_survey_type").on('change',function(){
      var stype = $(this).val();
      if(!stype){
        $("#list-itgoal").hide();
        $('.list-itgoal-purpose').hide();
        $('.list-itgoal-pain').hide();
      }else if(stype === '1-Purpose'){
        $("#list-itgoal").show();
        $('.list-itgoal-purpose').show();
        $('.list-itgoal-pain').hide();
      }else if(stype === '2-Pain'){
        $("#list-itgoal").show();
        $('.list-itgoal-pain').show();
        $('.list-itgoal-purpose').hide();
      }
    });

});

function initialize_select_user(id_element){
  $.ajax({
      type: 'GET',
      url: base_url+'/survey/ajax_get_list_user',
      // data: {
      //     'anakunit': idUnit
      // },
      success: function (data) {
          // the next thing you want to do 
          var $v_select = $(id_element);
          var item = JSON.parse(data);
          $v_select.empty();
          $v_select.append("<option value=''></option>");
          $.each(item, function(index,valuee) {        
              $v_select.append("<option value='"+valuee.id+"'>@"+valuee.username+"</option>");
          });

          //manually trigger a change event for the contry so that the change handler will get triggered
          $v_select.change();
      }
  });
}