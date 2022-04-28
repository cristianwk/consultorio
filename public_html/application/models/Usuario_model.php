<?php
/**
 * Created by PhpStorm.
 * User: bruno
 * Date: 10/31/15
 * Time: 11:19 AM
 */

class Usuario_Model extends CI_Model{
    var $id_usuario;
    var $nm_login;
    var $nm_login_hash;
    var $ps_login;
    var $ps_login_hash;
    var $id_endereco;
    var $id_perfil;
    var $ch_remocao;
    var $ds_observacao;
    var $email;
    var $data_cadastro;

    public function __construct()
    {
        parent::__construct();
    }

    public function adicionar($usuario)
    {
        return $this->db->insert('usuarios',$usuario);
    }

    public function getLastUsuario()
    {
        $this->db->order_by("id_usuario", "desc");
        $this->db->limit(1);
        return $this->db->get('usuarios')->result();
    }

    /**
     * @param $nm_login
     * @param $ps_login
     * @return array|bool
     */
    public function login($nm_login, $ps_login)
    {   
        //echo"<br><br>usuario MODEL:";
        //echo"<br>login: ".$nm_login;
        //echo"<br>pwd: ".$ps_login;
        
        $timezone = new DateTimeZone("America/Sao_Paulo");
        $dataIni = new DateTimeImmutable();//new DateTime("today", $timezone);
        //@$today = date('Y-m', $dataIni);
        $today = $dataIni->format('Y-m');
        $this->db->where('nm_login', $nm_login);
        $this->db->where('ps_login', $ps_login);
        $data = $this->db->get('usuarios')->result();
        $dt_atual = $dataIni->format('d');
        //echo"<br>data::<pre>";print_r($data);echo"</pre>";

        if (count($data)===1){
            if ($data[0]->id_perfil == 2) {
                //echo"<br>id: ".$data[0]->id_usuario;
                $x = $this->validaMensalidade($data[0]->id_usuario, $today);
                //echo"<br>valida mensalidade1:<pre> ";print_r($x);echo"</pre>";
                $plano = $this->getPlanoId($data[0]->id_usuario);
                //echo"<br>plano:".$plano[0]->id_plano;
                //echo"<br>plano: <pre>";print_r($plano);echo"</pre>";
                //echo "<br>valida mensalidade2:<pre> ";print_r($x);echo"</pre>";
                    if ($this->validaMensalidade(@$data[0]->id_usuario, @$today)) {//echo"<br>um";
                        $dados = array('usuario' => $data[0], 'logado' => TRUE);
                        //$this->session->set_userdata($dados);
                        $this->session->set_flashdata($dados);
                        $this->session->usuario;
                        $res = $this->session->usuario;

                        //$user = $data[0]->id_usuario;
                        //session_start();
                        foreach($res as $entry){
                            $_SESSION = [
                                'id_usuario' => $res->id_usuario,
                                'nm_login' => $res->nm_login,
                                'nm_login_hash' => $res->nm_login_hash,
                                'ps_login' => $res->ps_login,
                                'id_endereco' => $res->id_endereco,
                                'id_perfil' => $res->id_perfil,
                                'id_plano' => $res->id_plano,
                                'ch_remocao' => $res->ch_remocao,
                                'ds_observacao' => $res->ds_observacao,
                                'email' => $res->email,
                                'data_cadastro' => $res->data_cadastro
                            ];
                        }

                        //echo"<br>res::<pre>";print_r($_SESSION);echo"<pre>";exit;
                        //echo"<br>usuario::".$data[0]->id_usuario;exit;
                        //return $data[0]->id_usuario;
                        return $_SESSION;
                    } else {echo"<br>dois";
                        //se o plano for free
                        if($data[0]->id_plano == 1){echo"<br>tres";exit;
                            $dados = array('usuario' => $data[0], 'logado' => TRUE);
                            $this->session->set_userdata($dados);
                            return $result =  array('id_perfil' => $data[0]->id_perfil, 'saldoDevedor' => false);
                        }else{echo"<br>quatro data atual: ".$dt_atual;//exit;
                            if($dt_atual < 20) {
                                echo"<br>cinco";//exit;
                                $dados = array('usuario' => $data[0], 'logado' => TRUE);
                                $this->session->set_userdata($dados);
                                $this->session->usuario;
                                $res = $this->session->usuario;
                                echo "<br>retorna:<pre>";print_r($data[0]); echo"</pre> ";
                                return $data[0]->id_usuario;
                                //return $data[0];
                            } else {echo"<br>seis";//exit;
                                //entra aqui para cobrar o boloeto, e somente depois do dia 20
                                return $result = array('id_usuario' => $data[0]->id_usuario, 'id_perfil' => $data[0]->id_perfil, 'saldoDevedor' => true, 'id_plano' =>$plano[0]->id_plano);
                            }
                        }
                }
            } else {//echo "else";
                $dados = array('usuario' => $data[0], 'logado' => TRUE);
                $this->session->set_userdata($dados);
                return $result =  array('id_perfil' => $data[0]->id_perfil, 'saldoDevedor' => false);
            }
        }
        return false;
    }

    public function getUsuarioById($id)
    {
        $this->db->select('*');
        $this->db->from('usuarios as u');
        if (@$this->session->usuario->id_perfil == 1) {
            $this->db->join('pacientes as p', 'p.id_usuario = u.id_usuario');
        } else {
            $this->db->join('medicos as m', 'm.id_usuario = u.id_usuario');
        }
        $this->db->where('u.id_usuario', $id);
        return $this->db->get()->result();

    }

    public function getMedicoById($id)
    {   //echo"<br>id: ".$id;
        $this->db->select('*');
        $this->db->from('usuarios as u');
        $this->db->where('u.id_usuario', $id);
        return $this->db->get()->result();

    }

    public function update_password($id, $ps_login)
    {
        $dados['ps_login'] = md5($ps_login);
        $dados['ps_login_hash'] = $dados['ps_login'];
        return $this->db->where('id_usuario', $id)->update('usuarios',$dados);
    }

    public function update_recupera_password($email, $ps_login)
    {   //echo"aqui";exit;
        $dados['ps_login'] = md5($ps_login);
        $dados['ps_login_hash'] = $dados['ps_login'];
        return $this->db->where('email', $email)->update('usuarios',$dados);
    }

    public function pesquisa($nome, $cpf)
    {
        //$this->db->like('nr_cpf', $cpf);
        $this->db->like('nm_paciente', $nome);
        return $this->db->get('pacientes')->result();
    }

    public function validaMensalidade($id_usuario, $today)
    {
        //echo"<br>today: ".$today;
        //echo"<br>usuario: ".$id_usuario;
        $this->db->select('*');
        $this->db->from('pagamentos');
        $this->db->where('id_usuario', $id_usuario);
        $this->db->like('dt_pagamento', $today);
        $this->db->where('statusTransacao', 1);
        //echo "<br>SQL::".$this->db->last_query();
        //$result = $this->db->get()->result(); echo"<br>result:<pre> ";print_r($result);echo"</pre>";exit;
        return $this->db->get()->result();
    }

    public function getPlanoId($id)
    {
        $this->db->select('*');
        $this->db->from('usuarios as u');
        $this->db->join('planos as p', 'p.id_plano = u.id_plano');
        $this->db->where('u.id_usuario', $id);
        //$x = $this->db->get()->result();
        //echo"<br><pre>planoX: ";print_r($x);echo"</pre>";exit;
        //echo "<br>SQL::".$this->db->last_query();
        return $this->db->get()->result();
        //$return =  $this->db->get()->result();        
        //echo"<br><pre>result: ";print_r($return);echo"</pre>";exit;
    }

    public function getNumeroPacientes($id)
    {
        $this->db->select('count(*) as num_paciente');
        $this->db->from('medicos_pacientes ');
        $this->db->where('id_medico', $id);
        //$x = $this->db->get()->result();echo"<br>numero pacientes: <pre>";print_r($x);echo"</pre>";exit;
        return $this->db->get()->result();
    }

    public function getNumeroConsultas($id)
    {   //echo"<br>id: ".$id;
        $timezone = new DateTimeZone("America/Sao_Paulo");
        //$dataIni = new DateTime("today", $timezone);
        @$start_date = date('Y-m', $timezone);
        //$start_date = date('Y-m');
        $start_date = $start_date.'-01';
        $end_date = date('Y-m');
        $end_date = $end_date.'-30';

        $this->db->select('count(*) as consultas');
        $this->db->from('consultas');
        $this->db->where('id_medico', $id);
        $this->db->where('dt_consulta BETWEEN "'.$start_date. '" AND "'.$end_date. '"');
        //$x = $this->db->get()->result();echo"<br>numero consultas: ";echo"<pre>";print_r($x);echo"</pre>";exit;
        return $this->db->get()->result();
    }

}