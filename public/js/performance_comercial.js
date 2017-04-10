f7.ready(function(){
  var calendarRange = f7.calendar({
      input: '#periodo',
      dateFormat: 'M yyyy',
      rangePicker: true,
      toolbarCloseText: 'Cerrar',
      closeOnSelect: true,
      value: [new Date(1167696001000), new Date(1199145599000)],
  });

  var _canvas = '<canvas id="myChart" width="100%" height="60px"></canvas>';

  function getDates(value) {
    if(value && value.length == 2)
    {
      var from = new Date(value[0]);
      var to = new Date(value[1]);

      return [from.toUTCString(), to.toUTCString()];
    }

    return [];
  };

  function onError(error) {
    if(error.status == 422){
      f7.alert('Debe seleccionar todos los campos', 'Error');
      return;
    }

    f7.alert('No se pudo procesar su petición', 'Error');
  };

  function _ajax(el, onSuccess) {
    var url = $$(el).data('route');
    var data = {
      _token: $$('[name="_token"]').val(),
      periodo: getDates(calendarRange.value),
      consultores: $$('#select_consultores').val()
    };

    f7.showProgressbar('#progress-bar');

    $$.post(url, data, onSuccess, onError);
  };

  function drawCanvas(response) {
    var data = JSON.parse(response);
    var div = $$('#content');
    
    div.empty();
    div.append(_canvas);

    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, data);
  }

  $$(document).on('ajax:complete', function (e) {
    f7.hideProgressbar('#progress-bar');
  });

  $$('#relatorio').click(function() {
    _ajax(this, function(response) {
      $$('#content').html(response);
    });
  });

  $$('#grafico').click(function() {
    _ajax(this, drawCanvas);
  });

  $$('#pizza').click(function() {
    _ajax(this, drawCanvas);
  });
  
});