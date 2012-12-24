
function initOddsTooltips(){
  $('input.bet').focus(function(){
    if ($(this).prev().length){
        $(this).prev().show();
    }
  }).blur(function(){
    if ($(this).prev().length){
        $(this).prev().hide();
    }
  });
}
$(document).ready(function(){
    initOddsTooltips();
});
