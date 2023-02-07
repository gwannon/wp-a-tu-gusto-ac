<?php

function getFields($label = '') { 
  //Idiomas
  if ($label == 'langs') return array (
    array("id" => 18, "tag" => "newsletter-es", "text" => __('Castellano', 'wp-a-tu-gusto')),
    array("id" => 30, "tag" => "newsletter-eu", "text" => __('Euskera', 'wp-a-tu-gusto')),
  );

  if ($label == 'newsletters') return array( 
    array("id" => 19, "image" => "https://dummyimage.com/580x300/000000/fff", "automup" => 17, "automdown" => 215, "text" => __('Mi boletín semanal de Grupo SPRI', 'wp-a-tu-gusto'), "description" => __("Lo mejor de la semana: entrevistas, noticias, ayudas, agenda, formación y tendencias sobre ciberseguridad, digitalización, nuevos modelos de gestión avanzada, internacionalización, emprendimiento, infraestructuras.", 'wp-a-tu-gusto')),
    array("id" => 21, "image" => "https://dummyimage.com/580x300/000000/fff", "automup" => 18, "automdown" => 216, "text" => __('Mi boletín semanal de Adi! Agenda', 'wp-a-tu-gusto'), "description" => __("Si formas parte de la Nueva red de Statups de Euskadi, quieres invertir o arrancas ahora tu proyecto innovador, aquí te contamos toda lo que se mueve sobre emprendimiento.", 'wp-a-tu-gusto')),
    array("id" => 20, "image" => "https://dummyimage.com/580x300/000000/fff", "automup" => 19, "automdown" => 217, "text" => __('Mi boletín mensual UP! Euskadi', 'wp-a-tu-gusto'), "description" => __("Una vez al mes la información sobre la red de Startups vascas y sus últimas innovaciones.", 'wp-a-tu-gusto')),
    array("id" => 80, "image" => "https://dummyimage.com/580x300/000000/fff", "automup" => 46, "automdown" => 214, "text" => __('Mi boletín semanal Oferta y demanda tecnológica.', 'wp-a-tu-gusto'), "description" => __("Cada semana las oportunidades del mercado en materia de transferencia tecnológica internacional.", 'wp-a-tu-gusto')),
  ); 

  //Intereses
  if ($label == 'interests') return array (
    array("id" => 98, "tag" => "interes-ciberseguridad", "text" => __('Ciberseguridad ', 'wp-a-tu-gusto'), "automup" => 107, "automdown" => 108),
    array("id" => 101, "tag" => "interes-digitalizacion", "text" => __('Digitalización', 'wp-a-tu-gusto'), "automup" => 114, "automdown" => 115),
    array("id" => 96, "tag" => "interes-emprendimiento", "text" =>  __('Emprendimiento', 'wp-a-tu-gusto'), "automup" => 120, "automdown" => 121),
    array("id" => 105, "tag" => "interes-financiacion", "text" =>  __('Financiación', 'wp-a-tu-gusto'), "automup" => 126, "automdown" => 127),
    array("id" => 102, "tag" => "interes-i+d", "text" =>  __('I+D', 'wp-a-tu-gusto'), "automup" => 132, "automdown" => 133),
    array("id" => 107, "tag" => "interes-infraestructuras", "text" => __('Infraestructuras', 'wp-a-tu-gusto'), "automup" => 138, "automdown" => 139),
    array("id" => 97, "tag" => "interes-innovacion", "text" =>  __('Innovación', 'wp-a-tu-gusto'), "automup" => 144, "automdown" => 145),
    array("id" => 103, "tag" => "interes-internacionalizacion", "text" =>  __('Internacionalización', 'wp-a-tu-gusto'), "automup" => 150, "automdown" => 151),
    array("id" => 104, "tag" => "interes-invertir-en-euskadi", "text" =>  __('Invertir en Euskadi', 'wp-a-tu-gusto'), "automup" => 156, "automdown" => 157),
    array("id" => 106, "tag" => "interes-sostenibilidad-medioambiental", "text" =>  __('Sostenibilidad Medioambiental', 'wp-a-tu-gusto'), "automup" => 162, "automdown" => 163),
  );
  
  //Tipo de empresas
  if ($label == 'companies') return array (  
    array("id" => 117, "tag" => "empresa-personas-emprendedoras", "text" => __('Personas emprendedoras', 'wp-a-tu-gusto')),
    array("id" => 118, "tag" => "empresa-autonomos", "text" => __('Autonómos/as', 'wp-a-tu-gusto'), "position" => "empresa"),
    array("id" => 119, "tag" => "empresa-micropyme", "text" => __('Micropyme (1-10)', 'wp-a-tu-gusto'), "position" => "empresa"),
    array("id" => 120, "tag" => "empresa-pequena-empresa", "text" => __('Pequeña empresa (10-50)', 'wp-a-tu-gusto'), "position" => "empresa"),
    array("id" => 121, "tag" => "empresa-mediana-empresa", "text" => __('Mediana empresa (50-250)', 'wp-a-tu-gusto'), "position" => "empresa"),
    array("id" => 122, "tag" => "empresa-gran-empresa", "text" => __('Gran empresa (+250)', 'wp-a-tu-gusto'), "position" => "empresa"),
    array("id" => 123, "tag" => "empresa-agentes-rvcti", "text" => __('Agentes de la RVCTI', 'wp-a-tu-gusto'), "position" => "empresa"),
    array("id" => 124, "tag" => "empresa-asociaciones", "text" => __('Asociaciones', 'wp-a-tu-gusto'), "position" => "empresa"),
    array("id" => 125, "tag" => "empresa-estudiantes", "text" => __('Estudiantes', 'wp-a-tu-gusto'), "position" => "empresa"),
    array("id" => 126, "tag" => "empresa-inversores", "text" => __('Inversores', 'wp-a-tu-gusto'), "position" => "empresa"),
    array("id" => 127, "tag" => "empresa-agentes-intermedios", "text" => __('Agentes intermedios', 'wp-a-tu-gusto'), "position" => "empresa"),
  );

  //Notificaciones
  if ($label == 'notifications') return array (
    array("id" => 282, "tag" => "notificar-ayudas", "text" => __('Ayudas', 'wp-a-tu-gusto')),    
    array("id" => 280, "tag" => "notificar-eventos", "text" => __('Eventos', 'wp-a-tu-gusto')),
    array("id" => 281, "tag" => "notificar-documentacion", "text" => __('Informes de mercados y sectores', 'wp-a-tu-gusto')),
  );

  //Campos de datos personales
  if ($label == 'fields') return array( 
    array("id" => 40, "text" => __('Tratamiento', 'wp-a-tu-gusto'), "position" => "pre", 'required' => false, "select" => array(
      array("text" => __("Sr.", 'wp-a-tu-gusto'), "label" => "Sr."),
      array("text" => __("Sra.", 'wp-a-tu-gusto'), "label" => "Sra."),
    )),
    array("id" => 42, "text" => __('DNI/CIF/Pasaporte', 'wp-a-tu-gusto'), "position" => "post", 'required' => false),
    array("id" => 7, "text" => __('Provincia', 'wp-a-tu-gusto'), "position" => "post", 'required' => true, "select" => array(
      array("text" => __("Bizkaia", 'wp-a-tu-gusto'), "label" => "Bizkaia"),
      array("text" => __("Gipuzkoa", 'wp-a-tu-gusto'), "label" => "Gipuzkoa"),
      array("text" => __("Araba", 'wp-a-tu-gusto'), "label" => "Araba"),
      array("text" => __("Otros", 'wp-a-tu-gusto'), "label" => "Otros"),
    )),
    array("id" => 41, "text" => __('Nombre de empresa', 'wp-a-tu-gusto'), "position" => "post", 'required' => false),
    array("id" => 43, "text" => __('CIF', 'wp-a-tu-gusto'), "position" => "post", 'required' => false/*, 'pattern' => '(([X-Z]{1})([-]?)(\d{7})([-]?)([A-Z]{1}))|((\d{8})([-]?)([A-Z]{1}))'*/),
    array("id" => 44, "text" => __('Perfíl de Linkedin', 'wp-a-tu-gusto'), "position" => "post", 'required' => false, "type" => 'url', 'pattern' => '^http(s)?:\/\/(www\.)?linkedin\.com\/in\/.*$'),
    array("id" => 10, "text" => __('Sector', 'wp-a-tu-gusto'), "position" => "post", 'required' => false, "select" => array(
      array("text" => __("Administración gubernamental", 'wp-a-tu-gusto'), "label" => "Administración gubernamental"),
      array("text" => __("Aeronáutica/Aviación", 'wp-a-tu-gusto'), "label" => "Aeronáutica/Aviación"),
      array("text" => __("Alimentación", 'wp-a-tu-gusto'), "label" => "Alimentación"),
      array("text" => __("Automoción", 'wp-a-tu-gusto'), "label" => "Automoción"),
      array("text" => __("Banca de inversiones", 'wp-a-tu-gusto'), "label" => "Banca de inversiones"),
      array("text" => __("Biosalud", 'wp-a-tu-gusto'), "label" => "Biosalud"),
      array("text" => __("Capital de riesgo y capital privado", 'wp-a-tu-gusto'), "label" => "Capital de riesgo y capital privado"),
      array("text" => __("Construcción", 'wp-a-tu-gusto'), "label" => "Construcción"),
      array("text" => __("Contenidos digitales", 'wp-a-tu-gusto'), "label" => "Contenidos digitales"),
      array("text" => __("Energía", 'wp-a-tu-gusto'), "label" => "Energía"),
      array("text" => __("Equipos Ferroviarios", 'wp-a-tu-gusto'), "label" => "Equipos Ferroviarios"),
      array("text" => __("Fundición", 'wp-a-tu-gusto'), "label" => "Fundición"),
      array("text" => __("Industrías Marítimas", 'wp-a-tu-gusto'), "label" => "Industrías Marítimas"),
      array("text" => __("Investigación", 'wp-a-tu-gusto'), "label" => "Investigación"),
      array("text" => __("Maquinaria", 'wp-a-tu-gusto'), "label" => "Maquinaria"),
      array("text" => __("Medioambiente", 'wp-a-tu-gusto'), "label" => "Medioambiente"),
      array("text" => __("Papel", 'wp-a-tu-gusto'), "label" => "Papel"),
      array("text" => __("Petróleo y energía", 'wp-a-tu-gusto'), "label" => "Petróleo y energía"),
      array("text" => __("Seguridad del ordenador y de las redes", 'wp-a-tu-gusto'), "label" => "Seguridad del ordenador y de las redes"),
      array("text" => __("Servicios y tecnologías de la información", 'wp-a-tu-gusto'), "label" => "Servicios y tecnologías de la información"),
      array("text" => __("Siderurgia", 'wp-a-tu-gusto'), "label" => "Siderurgia"),
      array("text" => __("Tec. Av. Fabricación", 'wp-a-tu-gusto'), "label" => "Tec. Av. Fabricación"),
      array("text" => __("Telecomunicaciones", 'wp-a-tu-gusto'), "label" => "Telecomunicaciones"),
      array("text" => __("Transporte, movilidad y logística", 'wp-a-tu-gusto'), "label" => "Transporte, movilidad y logística"),
      array("text" => __("Videojuegos", 'wp-a-tu-gusto'), "label" => "Videojuegos"),
      array("text" => __("Otros", 'wp-a-tu-gusto'), "label" => "Otros")
    )),
  );

  //Campos de datos personales básicos
  return array(
    "nombre" =>  array("name" => __('Nombre', 'wp-a-tu-gusto'), 'required' => true),
    "apellidos" =>   array("name" => __('Apellidos', 'wp-a-tu-gusto'), 'required' => true),
    "telefono" =>   array("name" => __('Teléfono', 'wp-a-tu-gusto'), 'required' => false, 'type' => 'tel', 'pattern' => '[0-9]{9}')
  );
}