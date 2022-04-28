<?php

/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 10/27/15
 * Time: 10:43 PM
 */
class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model','usuario');
        $this->load->model('Endereco_Model','endereco');
        $this->load->model('Consulta_Model','consulta');
    }

    public function index()
    {
        $this->load->view('layout_principal/top');
        $this->load->view("template_login/form_login.php");
        $this->load->view('layout_principal/footer');
    }

    public function auth()
    {   
        //echo"<br>Estou no login Controller";
        //echo"<br>post: <pre>";print_r($_POST);echo"</pre>";
        $nm_login = $this->input->post('nm_login');
        $ps_login = md5($this->input->post('ps_login'));
        $txt = $this->usuario->login($nm_login, $ps_login);//vai em usuario_model.php:44
        //echo"<br>nm_login: ".$nm_login;die;

        $ajax = $this->input->post('requisicao');
        $urlRetorno = $this->input->post('urlRetorno');

        //echo"<br>post: <pre>";print_r($txt);echo"</pre>";die;

        $this->db->where('nm_login', $nm_login);
        $this->db->where('ps_login', $ps_login);
        $perfil = $this->db->get('usuarios')->result();

        //echo"Controller: <pre>txt: ";print_r($txt);echo"</pre>";
        //echo"<br>post: <pre>";print_r($perfil);echo"</pre>";

        if(empty($txt)){//echo"<br>aqui1";exit;
            $this->session->set_flashdata('msg', 'Error! - Login ou Senha não conferem!');
            return redirect('/login');
        }

        if (empty($txt['saldoDevedor'])){
            @$txt['saldoDevedor'] = false;//saldo devedor ok!
        }
        //echo"<br>saldo: ".$txt['saldoDevedor'];
        if (!empty($txt)){
            //echo"<br>txt: <pre>";print_r($txt);echo"</pre>";
            //echo"<br>perfilXX:<pre> ";print_r($perfil);echo"</pre>";
            //echo"<br>perfil id_usuario :".$perfil[0]->id_usuario."--";
            
            if ($txt['id_perfil'] == 1) {//echo"<br>perfil2 é um ".$txt['id_perfil'];exit;
                if($ajax == 'ajax'){//echo"<br>é asdf";exit;
                    echo json_encode( array( 'logado' => true ) );
                } else {//echo"<br>é paciente";exit;
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        session_start();
                    }
                    //echo"<br>idUsuario: ".$perfil[0]->id_usuario;
                    // cria variáveis de sessão e as inicializa com os dados do formulário:
                    $_SESSION['usuario']->id_usuario = $perfil[0]->id_usuario;
                    $usuario['id_usuario'] = $perfil[0]->id_usuario;
                    //echo"<br>session::".$_SESSION['usuario']->id_usuario;exit;
                    $id = $perfil[0]->id_usuario;      
                    //echo"session: <pre>";print_r($_SESSION);echo"</pre>";exit;              
                    //return redirect('/paciente/perfil');
                    $id = $this->session->usuario->id_usuario;
                    $dados['paciente'] = $this->usuario->getUsuarioById($id);
                    $dados['endereços'] = $this->endereco->getEnderecoUsuarioById($id);
                    $dados['consultas'] = $this->consulta->getConsultaPacienteId($id);
                    //echo"dados: <pre>";print_r($dados);echo"</pre>";exit;
                    $this->load->view('layout_principal/top');
                    $this->load->view("template_paciente/meus_dados", $dados);
                    $this->load->view('layout_principal/footer');
                }
            } elseif($txt['id_perfil'] == 2  && @$txt['saldoDevedor'] == 1){echo"<br>perfil é dois ".$txt['id_perfil'];exit;
                    $msg = "<font color='red' size='3'><B> Você possui mensalidades em aberto neste mês!  <a href='/pagamento/medico/" .$perfil['id_usuario'] . "/" . $txt['id_plano'] ."'><font color='red' size='3'><B>CLIQUE AQUI!</B></font></a> para realizar o pagamento!</B></font>";
                $this->session->set_flashdata('msg2', $msg);
                    return redirect('/login');
            } elseif ($txt['id_plano'] == 1){
                echo"<br>plano free";//exit;
                $id = $_SESSION['usuario']->id_usuario;//echo"<br>id: ".$id;exit;
                $n_pacientes = $this->usuario->getNumeroPacientes($id);//vai em usuario_model.php:168
                //echo"<pre>x: ";print_r($n_pacientes);echo"</pre>";exit;
                $num_paciente = $n_pacientes['0'];
                $pacientes = $num_paciente->num_paciente;

                @$consultas = $this->usuario->getNumeroConsultas($id);
                $num_consultas = $consultas['0'];
                $consultas = $num_consultas->consultas;

                if ($pacientes > '10'){
                    $msg = "<font color='red' size='3'><B>Você Atingiu o Limite Máximo de Pacientes, <a href='/medico/perfil'><font color='red' size='3'><B>CLIQUE AQUI!</B></font></a> para Mudar de Plano!</B></font> ";
                    $this->session->set_flashdata('msg2', $msg);
                    return redirect('/login');
                }elseif($consultas > '15'){
                    $msg = "<font color='red' size='3'><B> Você Atingiu o Limite Máximo de Consultas Agendadas, <a href='/medico/perfil'><font color='red' size='3'><B>CLIQUE AQUI!</B></font></a> para Mudar de Plano!</B></font> ";
                    $this->session->set_flashdata('msg2', $msg);
                    return redirect('/login');
                }
                else {echo"<br>asdf";exit;
                    return redirect('/medico/perfil');
                }
            }
            else {//echo"<br>aqui2";
                //se é plano pago e esta em dia entra aqui
                //echo"<pre>x: ";print_r($txt);echo"</pre>";exit;
                return redirect('/medico/perfil/');
                //$this->render('/medico/perfil/', $txt);
                //$this->load->view('medico/perfil', $txt);
            }
        } else {echo"<br>aqui3";exit;
            $this->session->set_flashdata('msg', 'Error! - Verifique seu login ou senha!');
            if($txt[0]->id_perfil == 2 && $txt[0]->saldoDevedor == true){
                echo "aqui";die;
                $this->session->set_flashdata('msg2', 'Você possui mensalidades em aberto neste mês!');
            }
            return redirect('/login');
        }

    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url('/'));
    }




}