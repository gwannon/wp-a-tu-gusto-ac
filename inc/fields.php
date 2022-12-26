<?php

function getFields($label = '') { 

if ($label == 'fields') return array( 
    array("id" => 40, "text" => __('Tratamiento', 'ac-update-forms'), "position" => "pre", 'required' => false, "select" => array(
      array("text" => __("Sr.", 'ac-update-forms'), "label" => "Sr."),
      array("text" => __("Sra.", 'ac-update-forms'), "label" => "Sra."),
    )),
    array("id" => 42, "text" => __('DNI/CIF/Pasaporte', 'ac-update-forms'), "position" => "post", 'required' => false),
    array("id" => 7, "text" => __('Provincia', 'ac-update-forms'), "position" => "post", 'required' => true, "select" => array(
      array("text" => __("Bizkaia", 'ac-update-forms'), "label" => "Bizkaia"),
      array("text" => __("Gipuzkoa", 'ac-update-forms'), "label" => "Gipuzkoa"),
      array("text" => __("Araba", 'ac-update-forms'), "label" => "Araba"),
      array("text" => __("Otros", 'ac-update-forms'), "label" => "Otros"),
    )),
    array("id" => 41, "text" => __('Nombre de empresa', 'ac-update-forms'), "position" => "post", 'required' => false),
    array("id" => 43, "text" => __('CIF', 'ac-update-forms'), "position" => "post", 'required' => false/*, 'pattern' => '(([X-Z]{1})([-]?)(\d{7})([-]?)([A-Z]{1}))|((\d{8})([-]?)([A-Z]{1}))'*/),
    array("id" => 44, "text" => __('Perfíl de Linkedin', 'ac-update-forms'), "position" => "post", 'required' => false, "type" => 'url', 'pattern' => '^http(s)?:\/\/(www\.)?linkedin\.com\/in\/.*$'),
    array("id" => 10, "text" => __('Sector', 'ac-update-forms'), "position" => "post", 'required' => false, "select" => array(
      array("text" => __("Administración gubernamental", 'ac-update-forms'), "label" => "Administración gubernamental"),
      array("text" => __("Aeronáutica/Aviación", 'ac-update-forms'), "label" => "Aeronáutica/Aviación"),
      array("text" => __("Alimentación", 'ac-update-forms'), "label" => "Alimentación"),
      array("text" => __("Automoción", 'ac-update-forms'), "label" => "Automoción"),
      array("text" => __("Banca de inversiones", 'ac-update-forms'), "label" => "Banca de inversiones"),
      array("text" => __("Biosalud", 'ac-update-forms'), "label" => "Biosalud"),
      array("text" => __("Capital de riesgo y capital privado", 'ac-update-forms'), "label" => "Capital de riesgo y capital privado"),
      array("text" => __("Construcción", 'ac-update-forms'), "label" => "Construcción"),
      array("text" => __("Contenidos digitales", 'ac-update-forms'), "label" => "Contenidos digitales"),
      array("text" => __("Energía", 'ac-update-forms'), "label" => "Energía"),
      array("text" => __("Equipos Ferroviarios", 'ac-update-forms'), "label" => "Equipos Ferroviarios"),
      array("text" => __("Fundición", 'ac-update-forms'), "label" => "Fundición"),
      array("text" => __("Industrías Marítimas", 'ac-update-forms'), "label" => "Industrías Marítimas"),
      array("text" => __("Investigación", 'ac-update-forms'), "label" => "Investigación"),
      array("text" => __("Maquinaria", 'ac-update-forms'), "label" => "Maquinaria"),
      array("text" => __("Medioambiente", 'ac-update-forms'), "label" => "Medioambiente"),
      array("text" => __("Papel", 'ac-update-forms'), "label" => "Papel"),
      array("text" => __("Petróleo y energía", 'ac-update-forms'), "label" => "Petróleo y energía"),
      array("text" => __("Seguridad del ordenador y de las redes", 'ac-update-forms'), "label" => "Seguridad del ordenador y de las redes"),
      array("text" => __("Servicios y tecnologías de la información", 'ac-update-forms'), "label" => "Servicios y tecnologías de la información"),
      array("text" => __("Siderurgia", 'ac-update-forms'), "label" => "Siderurgia"),
      array("text" => __("Tec. Av. Fabricación", 'ac-update-forms'), "label" => "Tec. Av. Fabricación"),
      array("text" => __("Telecomunicaciones", 'ac-update-forms'), "label" => "Telecomunicaciones"),
      array("text" => __("Transporte, movilidad y logística", 'ac-update-forms'), "label" => "Transporte, movilidad y logística"),
      array("text" => __("Videojuegos", 'ac-update-forms'), "label" => "Videojuegos"),
      array("text" => __("Otros", 'ac-update-forms'), "label" => "Otros")
    )),
  );

  return array(
    "nombre" =>  array("name" => __('Nombre', 'ac-update-forms'), 'required' => true),
    "apellidos" =>   array("name" => __('Apellidos', 'ac-update-forms'), 'required' => true),
    "telefono" =>   array("name" => __('Teléfono', 'ac-update-forms'), 'required' => false, 'type' => 'tel', 'pattern' => '[0-9]{9}')
  );
}