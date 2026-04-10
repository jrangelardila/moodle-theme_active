<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     theme_active
 * @category    string
 * @copyright   2024 Jhon Rangel <jrangelardila@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Tema Active';
$string['active:changesettings'] = 'Cambiar ajustes del tema';
$string['configtitle'] = 'Tema Active';
$string['generalsettings'] = 'General';

// Branding.
$string['brandingsettings'] = 'Identidad de marca';
$string['favicon'] = 'Favicon';
$string['favicon_desc'] = 'Suba un favicon (.ico o .png) que se mostrará en la pestaña del navegador.';
$string['logo'] = 'Logotipo';
$string['logo_desc'] = 'Suba el logotipo del sitio que se mostrará en la barra de navegación.';
$string['loginlogo'] = 'Logotipo de inicio de sesión';
$string['loginlogo_desc'] = 'Suba un logotipo que se mostrará en la página de inicio de sesión. Si se deja vacío, se usará el logotipo principal.';

// Login.
$string['loginsettings'] = 'Inicio de sesión';
$string['loginposition'] = 'Posición del formulario';
$string['loginposition_desc'] = 'Posición horizontal del formulario de inicio de sesión en escritorio. En móvil siempre se centra.';
$string['loginposition_left'] = 'Izquierda';
$string['loginposition_center'] = 'Centro';
$string['loginposition_right'] = 'Derecha';
$string['loginimage'] = 'Imagen de inicio de sesión';
$string['loginimage_desc'] = 'Logotipo o imagen que se muestra encima del formulario de inicio de sesión.';

// Layout.
$string['contentwidth'] = 'Ancho del contenido';
$string['contentwidth_desc'] = 'Ancho máximo del área de contenido principal (del 60 % al 100 %).';

// Colors.
$string['primarycolor'] = 'Color primario';
$string['primarycolor_desc'] = 'Color principal de la marca utilizado en todo el sitio (botones, enlaces, etc.).';
$string['secondarycolor'] = 'Color secundario';
$string['secondarycolor_desc'] = 'Color secundario utilizado para botones secundarios y acentos.';

// Footer.
$string['footersettings'] = 'Pie de página';
$string['footersettings_desc'] = 'Configurelos enlaces e iconos de redes sociales que se muestran en el pie de página del sitio.';

$string['facebook_url'] = 'Facebook';
$string['facebook_url_desc'] = 'URL completa de su página de Facebook (ej. https://facebook.com/tupagina).';
$string['twitter_url'] = 'Twitter / X';
$string['twitter_url_desc'] = 'URL completa de su perfil de Twitter/X (ej. https://x.com/tuperfil).';
$string['instagram_url'] = 'Instagram';
$string['instagram_url_desc'] = 'URL completa de su perfil de Instagram.';
$string['linkedin_url'] = 'LinkedIn';
$string['linkedin_url_desc'] = 'URL completa de su página de LinkedIn.';
$string['youtube_url'] = 'YouTube';
$string['youtube_url_desc'] = 'URL completa de su canal de YouTube.';
$string['tiktok_url'] = 'TikTok';
$string['tiktok_url_desc'] = 'URL completa de su perfil de TikTok.';

$string['moodleapp_android_url'] = 'App Moodle — Android (Google Play)';
$string['moodleapp_android_url_desc'] = 'URL de la app Moodle en Google Play Store.';
$string['moodleapp_ios_url'] = 'App Moodle — iOS (App Store)';
$string['moodleapp_ios_url_desc'] = 'URL de la app Moodle en la Apple App Store.';
// Footer labels.
$string['follow_social_media'] = 'Síganos';
$string['downloadapp'] = 'Descargue la app';
$string['information'] = 'Información';
$string['moodleapp_android'] = 'Google Play';
$string['moodleapp_ios'] = 'App Store';
$string['privacy'] = 'Política de privacidad';

// Course progress.
$string['courseprogress'] = 'Progreso del curso';
$string['progressof'] = '{$a->completed} de {$a->total} actividades completadas';
$string['activitylist'] = 'Lista de actividades';
$string['viewdetails'] = 'Ver detalles';
$string['overdue'] = 'Atrasada';
$string['manualcompletion'] = 'Marcar como hecha manualmente';
$string['backtocourse'] = 'Volver al curso';
$string['exportprint'] = 'Exportar / Imprimir';
$string['teacherscontact'] = 'Docentes del curso';
$string['sendmessage'] = 'Enviar mensaje';
$string['viewprofile'] = 'Ver perfil';
$string['generatedon'] = 'Generado el';
$string['student'] = 'Estudiante';
$string['instructor'] = 'Docente';
$string['restricted'] = 'Restringida';

// Forum discussion navigation.
$string['forumnav_aria'] = 'Navegación de la discusión';
$string['forumnav_prev'] = 'Discusión anterior';
$string['forumnav_next'] = 'Discusión siguiente';

// Calendar date-based color coding.
$string['calendar_past'] = 'Atrasada';
$string['calendar_today'] = 'Hoy';
$string['calendar_future'] = 'Próxima';

// Profile course listing.
$string['showallcourses'] = 'Mostrar todos los cursos ({$a} ocultos)';
$string['showvisibleonly'] = 'Mostrar solo los visibles';
$string['coursehidden'] = 'Oculto';

// Quick links (dashboard).
$string['quicklinks'] = 'Accesos rápidos';
$string['quicklinks_desc'] = 'Configurelos botones de acceso rápido que se muestran en el panel. Deje la etiqueta y la URL vacías para ocultar un espacio.';
$string['quicklink_number'] = 'Enlace {$a}';
$string['quicklink_label'] = 'Etiqueta';
$string['quicklink_label_desc'] = 'Texto mostrado en el botón.';
$string['quicklink_url'] = 'URL';
$string['quicklink_url_desc'] = 'URL completa a la que apunta el botón.';
$string['quicklink_icon'] = 'Icono';
$string['quicklink_icon_desc'] = 'Clase de FontAwesome sin el prefijo "fa-" (ej. "graduation-cap", "book", "globe"). Consulte los iconos en <a href="https://fontawesome.com/v4/icons/" target="_blank">fontawesome.com/v4/icons</a>.';
$string['quicklink_target'] = 'Abrir en';
$string['quicklink_target_desc'] = 'Dónde se debe abrir el enlace.';
$string['quicklink_target_blank'] = 'Nueva pestaña';
$string['quicklink_target_self'] = 'Misma ventana';
