<?php
// Clase Faq: Maneja las preguntas frecuentes (FAQ) del sistema.
// Permite crear, modificar, eliminar y listar FAQs asociadas a categorías.
class Faq {
    // Propiedades privadas de la FAQ
    private $id_faq;
    private $id_categoria;
    private $pregunta;
    private $respuesta;
    private $fecha_actualizacion;
    /**
     * Constructor: Inicializa un objeto Faq con los datos proporcionados
     * @param $id_faq
     * @param $id_categoria
     * @param $pregunta
     * @param $respuesta
     * @param $fecha_actualizacion
     */

    public function __construct($id_faq, $id_categoria, $pregunta, $respuesta, $fecha_actualizacion){
        $this->id_faq=$id_faq;
        $this->id_categoria=$id_categoria;
        $this->pregunta=$pregunta;
        $this->respuesta=$respuesta;
        $this->fecha_actualizacion=$fecha_actualizacion;

    }

    /************************************* GETTERS y SETTERS ***********************************/
    /*******************************************************************************************/

    public function getIdFaq(){
        return $this->id_faq;
    }
    public function getPregunta(){
        return $this->pregunta;
    }
    public function getRespuesta(){
        return $this->respuesta;
    }
    public function getIdCategoria(){
        return $this->id_categoria;
    }
    public function getFecha(){
        return $this->fecha_actualizacion;
    }

    // Setters para modificar las propiedades
    public function setPregunta($pregunta){
        $this->pregunta=$pregunta;
    }
    public function setRespuesta($respuesta){
        $this->respuesta=$respuesta;
    }
    public function setIdCategoria($id_categoria){
        $this->id_categoria=$id_categoria;
    }
    public function setFecha($fecha_actualizacion){
        $this->fecha_actualizacion=$fecha_actualizacion;
    }

    /********************************* METODOS **********************************/
    /****************************************************************************/

    /**
     * Metodo para crear una nueva FAQ en la base de datos
     * @return void
     */
    public function InsertarFAQ(): void{
        $db = DB::conectar();
        $stmt = $db->prepare("INSERT INTO faq (id_faq, pregunta, respuesta, fecha_actualizacion, id_categoria) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$this->id_faq, $this->pregunta, $this->respuesta, $this->fecha_actualizacion, $this->id_categoria]);
    }
    /**
     * Metodo para modificar el FAQ de la BD
     * @return void
     */
    public function ActualizarFAQ(){
        $db = DB::conectar();
        $stmt = $db->prepare("UPDATE faq SET pregunta = ?, respuesta = ?, fecha_actualizacion = ?, id_categoria = ? WHERE id_faq = ?");
        $stmt->execute([$this->pregunta, $this->respuesta, $this->id_categoria, $this->id_faq, $this->fecha_actualizacion]);
    }
    /**
     * Metodo para eliminar FAQ de la BD
     * @return void
     */
    public function EliminarFAQ(): void{
        $db = DB::conectar();
        $stmt = $db->prepare("DELETE FROM faq WHERE id_faq = ?");
        $stmt->execute([$this->id_faq]);
    }

    /**
     * Obtiene de la BD todos los FAQ
     * @return array objetos Faq
     */
    public static function ListarFAQ() :array{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT * FROM faq");
        $stmt->execute();
        $faqs = array();
        while ($consultaFaq = $stmt->fetch(PDO::FETCH_ASSOC)){
            $faqs[] = new Faq(
                $consultaFaq['id_faq'],
                $consultaFaq['id_categoria'],
                $consultaFaq['pregunta'],
                $consultaFaq['respuesta'],
                $consultaFaq['fecha_actualizacion']
            );
        }
        return $faqs;
    }
    /**
     * Obtiene de la BD todos los FAQ de una categoria especifica
     * @param $id_categoria
     * @return array objetos Faq
     */
    public static function ListarFAQPorCategoria($id_categoria) :array{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT * FROM faq WHERE id_categoria = ?");
        $stmt->execute([$id_categoria]);
        $faqs = array();
        while ($consultaFaq = $stmt->fetch(PDO::FETCH_ASSOC)){
            $faqs[] = new Faq(
                $consultaFaq['id_faq'],
                $consultaFaq['id_categoria'],
                $consultaFaq['pregunta'],
                $consultaFaq['respuesta'],
                $consultaFaq['fecha_actualizacion']
            );
        }
        return $faqs;
    }

    /**
     * Devuelve cual es el siguiente id de faq en BD
     * @return int
     */
    public static function SiguienteId() :int {
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT MAX(id_faq) as id_faq FROM faq");
        $stmt->execute();
        $id_faq = $stmt->fetch(PDO::FETCH_ASSOC);
        return $id_faq['id_faq'] + 1;
    }

    /**
     * Devuelve un Faq segun su id
     * @param $id_faq
     * @return Faq
     */
    public static function getFaqById($id_faq): Faq{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT * FROM faq WHERE id_faq = ?");
        $stmt->execute([$id_faq]);
        $faq = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Faq(
            $faq['id_faq'],
            $faq['id_categoria'],
            $faq['pregunta'],
            $faq['respuesta'],
            $faq['fecha_actualizacion']
        );
    }
}