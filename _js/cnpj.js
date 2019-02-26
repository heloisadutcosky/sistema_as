$(document).ready( function() {
	 /* Executa a requisição quando o campo CNPJ perder o foco */
	 $('#cnpj').blur(function(){
					 /* Configura a requisição AJAX */
					 $.ajax({
								url: '../../_js/consultar_cnpj.php',
								type : 'POST', /* Tipo da requisição */ 
								data: 'cnpj=' + $('#cnpj').val(), /* dado que será enviado via POST */
								dataType: 'json', /* Tipo de transmissão */
								success: function(data){
												$('#nome').val(data.nome);

												//$("#post #fantasia").val(resposta.fantasia);
												//$("#post #atividade").val(resposta.atividade_principal[0].text + " (" + resposta.atividade_principal[0].code + ")");
												//$("#post #telefone").val(resposta.telefone);
												//$("#post #email").val(resposta.email);
												$('#logradouro').val(data.logradouro);
												$('#complemento').val(data.complemento);
												$('#bairro').val(data.bairro);
												$('#cidade').val(data.municipio);
												$('#estado').val(data.uf);
												$('#cep').val(data.cep);
												$('#numero').val(data.numero);
 
												$('#inscricao_estadual').focus();
								}
					 });   
	 return false;    
	 })
});