<?php
defined ('ABSPATH') or die ('¡No HACKS Man!');//bloquear acceso por url
/*
 * Plugin Name: Agregar Templates
 * Plugin URI: https://www.wpexplorer.com/wordpress-page-templates-plugin/
 * Description: Agregar templates creados, se ha creado un shorcode que interactua según su rol.
 * Version: 1.0.0
 * Author: Miguel Peña 
*/
class PageTemplater {
	/**
	 * Una referencia a una instancia de esta clase.
	 */
	private static $instance;
	/**
	 * La matriz de plantillas que este complemento rastrea.
	 */
	protected $templates;
	/**
	 * Devuelve una instancia de esta clase.
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new PageTemplater();
		}
		return self::$instance;
	}
	/**
	 * Inicializa el complemento configurando filtros y funciones de administración.
	 */
	private function __construct() {
		$this->templates = array();
		// Agregar un filtro al metabox de atributos para inyectar una plantilla en el caché.
		if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {
			// 4.6 y mayores
			add_filter(
				'page_attributes_dropdown_pages_args',
				array( $this, 'register_project_templates' )
			);
		} else {
			// Agregar un filtro al metabox de atributos de la versión wp 4.7
			add_filter(
				'theme_page_templates', array( $this, 'add_new_template' )
			);
		}
		// Añadir un filtro a la publicación de guardar para inyectar la plantilla en el caché de la página.
		add_filter(
			'wp_insert_post_data',
			array( $this, 'register_project_templates' )
		);

		// Agregar un filtro a la plantilla incluir para determinar si la página tiene nuestro.
		// Plantilla asignada y devolver su ruta.
		add_filter(
			'template_include',
			array( $this, 'view_project_template')
		);

		// Añade tus plantillas a esta matriz.
		$this->templates = array(
			'pages/1/Prueba-Template.php' => 'Prueba-Template',// Template 1
            'pages/2/Template-2.php' => 'Template-2' //Template 2
		);
	}
	/**
	 * Agrega nuestra plantilla a la página desplegable para v4.7 +
	 *
	 */
	public function add_new_template( $posts_templates ) {
		$posts_templates = array_merge( $posts_templates, $this->templates );
		return $posts_templates;
	}
	/**
	 * Agrega nuestra plantilla al caché de páginas para engañar a WordPress
     * en pensar que el archivo de plantilla existe donde realmente no existe.
	 */
	public function register_project_templates( $atts ) {
		// Crea la clave utilizada para el caché de temas.
		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
		// Recuperar la lista de caché.
		// Si no existe, o si está vacío, prepara una matriz.
		$templates = wp_get_theme()->get_page_templates();
		if ( empty( $templates ) ) {
			$templates = array();
		}
		// Nuevo caché, por lo tanto, elimine el antiguo
		wp_cache_delete( $cache_key , 'themes');
		// Ahora agregue nuestra plantilla a la lista de plantillas fusionando nuestras plantillas.
		// Con la matriz de plantillas existente desde el caché.
		$templates = array_merge( $templates, $this->templates );
		// Agregue el caché modificado para permitir que WordPress lo recoja para su listado.
		// Plantillas disponibles.
		wp_cache_add( $cache_key, $templates, 'themes', 1800 );
		return $atts;
	}
	/**
	 * Comprueba si la plantilla está asignada a la página.
	 */
	public function view_project_template( $template ) {
		// Devuelva la plantilla de búsqueda si estamos buscando (en lugar de la plantilla para el primer resultado)
		if ( is_search() ) {
			return $template;
		}
		// Obtener publicación global
		global $post;
		// Volver plantilla si la publicación está vacía
		if ( ! $post ) {
			return $template;
		}
		// Devolver plantilla predeterminada si no tenemos una personalizada definida
		if ( ! isset( $this->templates[get_post_meta(
			$post->ID, '_wp_page_template', true
		)] ) ) {
			return $template;
		}
		// Permite filtrar la ruta del archivo.
		$filepath = apply_filters( 'page_templater_plugin_dir_path', plugin_dir_path( __FILE__ ) );
		$file =  $filepath . get_post_meta(
			$post->ID, '_wp_page_template', true
		);
		// Solo para estar seguros, verificamos si el archivo existe primero.
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}
		// Devolver plantilla
		return $template;
	}
}
add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );
/*Fin de agregar templates*/

/*shorcode*/
function shortcode_gracias() {
   $usuario = wp_get_current_user();
   $rol =(array) $usuario->roles[0];/*print_r() Imprimir array completo*/
   if ($rol[0] == 'administrator'){ // OR == !is_admin
      return '<p>Condicional aprovada, eres administrador.</p>';
   } else {
      return '<p>Condicional denegada, no eres admin.</p>'; 
   }
}
add_shortcode('gracias', 'shortcode_gracias');
/*echo do_shortcode("[gracias]");*///Para imprimir shortcode
/*fin shorcode*/

add_filter( 'template_include', 'Script_En_Sitio', 1000 );
function Script_En_Sitio( $template ){
   if (is_page_template('pages/1/Prueba-Template.php')){
      function Js_Prueba_Template() {
         $handleJs = 'Prueba-Template'; //nombre del script
         $srcJs = plugins_url().'/Templates/pages/1/js/Prueba-Template.js';//ruta de archivo (Get template directory uri, retorna la url del tema)
         $depsJs = array('jquery');//dependencias de js (llamar librería)
         $ver = '1.0.0';//version de js
         $in_footer = true;//si el código se agregará al footer o no
         wp_register_script( $handleJs, $srcJs, $depsJs, $ver, $in_footer );//Hook para poner en cola scripts
         wp_enqueue_script( $handleJs );

         $handleJs = 'Bootstrap-Js'; //nombre del script
         $srcJs = plugins_url().'/Templates/pages/js/bootstrap.min.js';
         wp_register_script( $handleJs, $srcJs);//Hook para poner en cola
         wp_enqueue_script( $handleJs );

         $handleJs = 'Stellar-Parallax'; //nombre del script
         $srcJs = plugins_url().'/Templates/pages/js/jquery.stellar.min.js';
         wp_register_script( $handleJs, $srcJs);//Hook para poner en cola
         wp_enqueue_script( $handleJs );

         $handleJs = 'Browser-Selector'; //nombre del script
         $srcJs = plugins_url().'/Templates/pages/js/css_browser_selector.js';
         wp_register_script( $handleJs, $srcJs);//Hook para poner en cola
         wp_enqueue_script( $handleJs );         
         
         $handleJs = 'Bootstrap-Css'; //nombre del script
         $srcJs = plugins_url().'/Templates/pages/css/bootstrap.min.css';
         wp_register_style( $handleJs, $srcJs);//Hook para poner en cola scripts
         wp_enqueue_style( $handleJs );
      }
      add_action('wp_enqueue_scripts','Js_Prueba_Template');
   }
   return $template;
}

function verificar_usuario_curso_aps(){
      add_action(‘wplms_course_subscribed’,
      function($current_user->ID,$curso_aps_id){
         echo 'Cursando curso';   
      },10,2);
}

add_shortcode('Verificar-Usuario-Curso-Aps','verificar_usuario_curso_aps');