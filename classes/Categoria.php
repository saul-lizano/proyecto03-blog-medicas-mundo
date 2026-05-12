<?php
class Categoria{
    //identificador de la categoria
    private int $id_categoria;
    private string $nombre;
    private string $descripcion;
    //orden en la que se muestre la categoria
    private int $orden;
    private string $img_cat;
    //identificador de la categoria a la que pertenece
    private $id_madre;
    private string $fecha_actualizacion;
    private PDO $conn;

    /**
     * @param $id_categoria
     * @param $nombre
     * @param $descripcion
     * @param $orden
     * @param $img_cat
     * @param $id_madre
     * @param $fecha_actualizacion
     */
    public function __construct($id_categoria, $nombre, $descripcion, $orden, $img_cat, $id_madre, $fecha_actualizacion)
    {
        $this->id_categoria = $id_categoria;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->orden = $orden;
        $this->img_cat = $img_cat;
        $this->id_madre = $id_madre;
        $this->fecha_actualizacion = $fecha_actualizacion;
    }

    /**
     * Devuelve el siguiente identificador de categoria segun BD
     * @return int
     */
    public static function SiguienteId(): int{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT MAX(id_categoria) as id_categoria FROM categoria");
        $stmt->execute();
        $id_categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        return $id_categoria['id_categoria'] + 1;
    }

    /**
     * Devuelve el orden siguiente de los contenidos de esa categoria
     * @param $id_categoria int categoria en la que buscar
     * @return int|mixed
     */
    public static function SiguienteOrden($id_categoria){
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT MAX(orden) as orden FROM categoria WHERE id_madre = ?");
        $stmt->execute([$id_categoria]);
        $orden = $stmt->fetch(PDO::FETCH_ASSOC);
        return $orden['orden'] + 1;
    }
    public static function BuscarCategorias(string $termino): array {
        $db = DB::conectar();
        // Usamos LIKE para buscar coincidencias parciales en nombre o descripción
        $stmt = $db->prepare("SELECT * FROM categoria WHERE nombre LIKE ? OR descripcion LIKE ?");
        $busqueda = "%" . $termino . "%";
        $stmt->execute([$busqueda, $busqueda]);

        $categorias = array();
        try {
            while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categorias[] = new Categoria(
                    $categoria['id_categoria'],
                    $categoria['nombre'],
                    $categoria['descripcion'],
                    $categoria['orden'],
                    $categoria['img_cat'],
                    $categoria['id_madre'],
                    $categoria['fecha_actualizacion']
                );
            }
        } catch (PDOException $e) {
            error_log("Error en la búsqueda: " . $e->getMessage());
        }
        return $categorias;
    }

    /**
     * Actualiza la categoria en la BD
     * @return void
     */
    public function ActualizarCategoria(): void{
        $db = DB::conectar();
        $stmt = $db->prepare("UPDATE categoria SET nombre= ?, descripcion= ?, orden= ?, img_cat= ?, id_madre= ?, fecha_actualizacion= ? WHERE id_categoria= ?");
        $stmt->execute([$this->nombre, $this->descripcion, $this->orden, $this->img_cat, $this->id_madre, $this->fecha_actualizacion, $this->id_categoria]);
    }

    /**
     * Crea una nueva categoria en la BD
     * @return void
     */
    public function InsertarCategoria() :void {
        try {
            $db = DB::conectar();
            $stmt = $db->prepare("INSERT INTO categoria(id_categoria, nombre, descripcion, orden, img_cat, id_madre, fecha_actualizacion) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$this->id_categoria, $this->nombre, $this->descripcion, $this->orden, $this->img_cat, $this->id_madre, $this->fecha_actualizacion]);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    /**
     * Elimina la categoria en la BD
     * @return void
     */
    public function EliminarCategoria() :void {
        try {
            $db = DB::conectar();
            $faqs = Faq::ListarFAQPorCategoria($this->id_categoria);
            foreach ($faqs as $faq) {
                $faq->EliminarFAQ();
            }
            $bloques = Bloque::getBloquesByCategoria($this->id_categoria);
            foreach ($bloques as $bloque) {
                $bloque->EliminarBloque();
            }
            $subcategorias = Categoria::getSubcategorias($this->id_categoria);
            foreach ($subcategorias as $subcategoria) {
                $subcategoria->EliminarCategoria();
            }
            $stmt = $db->prepare("DELETE FROM categoria WHERE id_categoria = ?");
            $stmt->execute([$this->id_categoria]);
            if (file_exists("/Medicas-del-Mundo/styles/img/". $this->img_cat) && $this->img_cat != "placeholder_categoria.jpg") {
                unlink($this->img_cat);
            }
        } catch (PDOException $e) {
            // Aquí capturamos el error si la base de datos bloquea el borrado
            // Puedes guardar el error en un log, o redirigir con un mensaje de error por GET
            // Ejemplo: header("Location: error.php?msg=No puedes borrar una categoria con subcategorias");
            error_log("Error al eliminar categoría: " . $e->getMessage());
        }
    }

    /**
     * Devuelve un array con los objetos de categoria que haya en la BD
     *
     * @return array | string objetos Categoria o un string en caso de error
     */
    public static function getCategorias() :array|string
    {
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT * FROM categoria WHERE id_madre IS NULL");
        $stmt->execute();
        $categorias = array();
        try {
            while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categoria = new Categoria(
                    $categoria['id_categoria'],
                    $categoria['nombre'],
                    $categoria['descripcion'],
                    $categoria['orden'],
                    $categoria['img_cat'],
                    $categoria['id_madre'],
                    $categoria['fecha_actualizacion']
                );
                $categorias[] = $categoria;
            }
        }
        catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
        return $categorias;
    }
    /**
     * Devuelve un array con las subcategorias pertenecientes a una categoria
     *
     * @param $id_madre int id de la categoria madre a la que pertenece la subcategoria
     * @return array | string objetos Categoria o el error de la consulta
     */
    public static function getSubcategorias($id_madre) :array | string
    {
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT * FROM categoria WHERE id_madre = ?");
        $stmt->execute([$id_madre]);
        $subcategorias = array();
        try {
            while ($subcategoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $subcategoria = new Categoria(
                    $subcategoria['id_categoria'],
                    $subcategoria['nombre'],
                    $subcategoria['descripcion'],
                    $subcategoria['orden'],
                    $subcategoria['img_cat'],
                    $subcategoria['id_madre'],
                    $subcategoria['fecha_actualizacion']
                );
                $subcategorias[] = $subcategoria;
            }
        }
        catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
        return $subcategorias;
    }
    public static function getCategoriaById($id_categoria) :Categoria{
        $db = DB::conectar();
        $stmt = $db->prepare("SELECT * FROM categoria WHERE id_categoria = ? ");
        $stmt->execute([$id_categoria]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);
        return new Categoria(
            $categoria['id_categoria'],
            $categoria['nombre'],
            $categoria['descripcion'],
            $categoria['orden'],
            $categoria['img_cat'],
            $categoria['id_madre'],
            $categoria['fecha_actualizacion']
        );
    }


    /**************************************** GETTERS Y SETTERS **************************************/
    public function getIdCategoria()
    {
        return $this->id_categoria;
    }
    public function setIdCategoria($id_categoria)
    {
        return $this->id_categoria = $id_categoria;
    }
    public function getOrden()
    {
        return $this->orden;
    }
    public function setOrden($orden)
    {
        return $this->orden = $orden;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        return $this->nombre = $nombre;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function setDescripcion($descripcion)
    {
        return $this->descripcion = $descripcion;
    }
    public function getImg()
    {
        return $this->img_cat;
    }
    public function setImg($img)
    {
        return $this->img_cat = $img;
    }
    public function getIdMadre()
    {
        return $this->id_madre;
    }
    public function setIdMadre($id_madre)
    {
        return $this->id_madre = $id_madre;
    }
    public function getFechaActualizacion()
    {
        return $this->fecha_actualizacion;
    }
    public function setFechaActualizacion($fecha_actualizacion)
    {
        return $this->fecha_actualizacion = $fecha_actualizacion;
    }
}