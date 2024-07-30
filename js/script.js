$(document).ready(function(){
  $('#produtos ul li a#adicionar').click(function(a){
    window.location.replace(this.href+"&qnt="+window.prompt("Qual o nº de produtos você deseja adicionar?"));
    return false;
  });
});
