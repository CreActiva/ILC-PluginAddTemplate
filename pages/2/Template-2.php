<?php
defined ('ABSPATH') or die ('¡No HACKS Man!');//bloquear acceso por url
/*
 * Template Name: Template-2
 * Description: Template 2
 */
/*Obtener rol de usuario actualmente logueado*/
$usuario = wp_get_current_user();
$rol =(array) $usuario->roles[0];/*print_r() Imprimir array completo*/
print ($rol[0]);

/*Obtener Miembro buddypress*/
$user_id = wp_get_current_user();
$type = bp_get_member_type( $user_id->ID );