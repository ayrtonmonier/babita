<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Database;

use Helpers\Data;
use Helpers\Encrypt;
use Helpers\Validations;
use Helpers\Url;
use Helpers\MyCurl;

class Testes
{

	public $db;
	
	public function __construct()
	{
		$this->db = Database::get();
	}
	
    public function index()
    {
    	
    	$data['subtitle'] = 'Welcome';
    	
    	View::render('header', $data);
    	View::render('home');
    	View::render('footer');
    	
    }
	
    public function teste()
    {
    	$x = MyCurl::get( DIR.'/testes/request', array('msg' => 'Message 1'), DIR.'/testesRest' );
    	View::output($x, 'text' );
    }

    public function request()
    {
    	View::output($_REQUEST, 'text');
    	
    	echo "<hr>";
    	
    	View::output($_SERVER, 'text');
		
		echo "<hr>";
		
		if($_SERVER['REQUEST_METHOD'] === "PUT"){
			parse_str(file_get_contents('php://input', false , null, -1 , $_SERVER['CONTENT_LENGTH'] ), $_PUT);
		}
		else{
			$_PUT = array();
		}

		View::output($_PUT, 'text');

		//View::output($putData, 'text');
    }

    public function info()
    {
    	phpinfo();
    }
    

    public function removeAcentos()
    {
    	echo Validations::removeAccents("Fábio Assunção da Silva");
    }
 
    
	public function mail(){
		
		$param['type'] = 'smtp';
		
		$mail = new \Helpers\PhpMailer\Mail($param);
		
		$assunto = "BF1 - Teste email";
		$mensagem = "Mensagem teste Babita Framework 1 (ÁÂÀÄÇ$!@&%)";
		
		$from = array('mail' => 'fabiioassuncao@gmail.com', 'name' => 'Babita Framework 1');
		
		$replyTo = array('mail' => 'fabio@fabioassuncao.com.br', 'name' => 'Contato BF1');
		
		$destino = array(
				array('mail' => 'fabio23gt@gmail.com', 'name' => 'Fábio Assunção'),
				array('mail' => 'fabio.as@live.com', 'name' => 'Adriano Marinho'),
			);

		$result = $mail->quick($assunto, $mensagem, $from, $destino, $replyTo);
		View::output($result, 'vd');
		
	}
	
	public function mailTemplate(){
		
		$mail = new \Helpers\PhpMailer\Mail();
		
		$data = array(
				'title' => 'Babita Framework 1',
				'subtitle' => 'Teste com template',
				'name' => 'Adriano Marinho',
				'link' => '#',
				'link_name' => 'Botão'
				);
		
		$mail->subject("BF1 - email com anexo e template :)");
		$mail->template('teste.html', $data);
		$mail->destination(array(
				//"aangelomarinho@gmail.com, Adriano Marinho",
				"fabio23gt@gmail.com, Fábio Assunção"
		));

		$mail->attachment('uploads/teste_anexo.png, teste_anexo.png');
		
		$mail->from(array('mail' => 'fabio@fabioassuncao.com.br', 'name' => 'Babita Framework 1'), false);
		$mail->replyTo(array('mail' => 'contato@bf1.com', 'name' => 'BF1 Contato novo'));

 		$result = $mail->go();
 		View::output($result, 'vd');
	
	}
	
	public function mailList(){
	
		$mail = new \Helpers\PhpMailer\Mail();
	
		$data = array(
				'title' => 'Babita Framework 1',
				'subtitle' => 'Test mailing list with template',
				'name' => 'User test',
				'link' => '#',
				'link_name' => 'Botão'
		);
	
		$mail->subject("BF1 - mailing list with template");
		$mail->template('teste.html', $data);
		$mail->from(array('mail' => 'fabio@fabioassuncao.com.br', 'name' => 'Babita Framework 1'));
		
		$result = $mail->mailingList(array(
				"aangelomarinho@gmail.com, Adriano Marinho",
				"fabio23gt@gmail.com, Fábio Assunção",
				"fabio.as@live.com, Fabio AS",
				"fabio@ma.ip.tv, Fabio IPTV",
				"fabiioassuncao@gmail.com, Fabio IPTV"
		));

		View::output($result, 'json');
	
	}
	
	public function mailListLoop(){
	
		$mail = new \Helpers\PhpMailer\Mail();
		$mail->subject("BF1 - mailing list with template");
		$mail->from(array('mail' => 'fabio@fabioassuncao.com.br', 'name' => 'Babita Framework 1'));

		$list = array(
			array('mail' => 'aangelomarinho@gmail.com', 'name' => 'Adriano Marinho'),
			array('mail' => 'fabio23gt@gmail.com', 		'name' => 'Fábio Assunção'),
			array('mail' => 'fabio.as@live.com', 		'name' => 'Fabio AS'),
			array('mail' => 'fabio@ma.ip.tv', 			'name' => 'Fabio IPTV'),
			array('mail' => 'fabiioassuncao@gmail.com', 'name' => 'Fabio 2 GMAIL')
		);
		
		foreach($list as $l){
			
			$mail->template('teste.html', array(
					'title' => 'Babita Framework 1',
					'subtitle' => 'Test mailing list with template',
					'name' => $l['name'],
					'link' => '#',
					'link_name' => $l['mail']
			));
			
			$mail->destination( array('mail' => $l['mail'], 'name' => $l['name']), true );
			
		}
		
		View::output($mail->log(), 'json');
	
	}
}