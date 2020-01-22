<?php
defined ('ABSPATH') or die ('¡No HACKS Man!');//bloquear acceso por url
/*
 * Template Name: Prueba-Template
 * Description: Prueba de template
 */
get_header(); ?>
<!--HTML--->
<div class="wrap"><div id="primary" class="content-area"><main id="main" class="site-main" role="main">
<?php
/*Obtener rol de usuario actualmente logueado*/

$usuario = wp_get_current_user();
$rol =(array) $usuario->roles[0];/*print_r() Imprimir array completo*/
echo '<p>'.$rol[0].'</p>';

echo do_shortcode("[gracias]");

echo get_page_template_slug();//Slug de template

if(is_page_template('pages/1/Prueba-Template.php')){
   echo '<p>funciona comparación de templates</p>';
}
?>
<div class="col-6 p-0 mx-auto">
   Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis a modi aut voluptates aperiam saepe! Dolore porro, nemo architecto, cupiditate, quas et magnam delectus laudantium, aspernatur odit modi blanditiis mollitia.
</div>
<div class="col-6 p-0 mx-auto pt-4"><button class="btn btn-primary w-100">Hola</button></div>
<!--Fin HTML--->
</main></div></div>
<?php
echo do_shortcode("[Verificar-Usuario-Curso-Aps]");
?>
<div class="w-100 mt-3" style="background:url('http://localhost/academia/wp-content/uploads/2018/09/asd7.jpeg') no-repeat center right; height:50vh;"
   data-stellar-background-ratio="0.7"></div>
   
   
<?php 
get_footer();