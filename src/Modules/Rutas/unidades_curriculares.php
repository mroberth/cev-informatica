<?php
use Core\Http\Router;

Router::get('a/unidades-curriculares/crear', function(){
    cargar_controlador('UnidadesCurriculares', 'unidadCurricularController.php');
    crear_unidad_curricular();
});

Router::get('a/unidades-curriculares/consultar', function(){
    cargar_controlador('UnidadesCurriculares', 'unidadCurricularController.php');
    consultar_unidades_curriculares();
});

Router::get('a/unidades-curriculares/obtener_trayectos', function(){
    cargar_controlador('UnidadesCurriculares', 'unidadCurricularController.php');
    obtener_trayectos();
});

Router::get('a/unidades-curriculares/obtener_fases', function(){
    cargar_controlador('UnidadesCurriculares', 'unidadCurricularController.php');
    obtener_fases();
});

Router::get('a/unidades-curriculares/verificar_codigo', function(){
    cargar_controlador('UnidadesCurriculares', 'unidadCurricularController.php');
    verificar_codigo_uc();
});

Router::post('a/unidades-curriculares/registrar', function(){
    cargar_controlador('UnidadesCurriculares', 'unidadCurricularController.php');
    registrar_unidad_curricular();
});

Router::get('a/unidades-curriculares/consultar_uc', function(){
    cargar_controlador('UnidadesCurriculares', 'unidadCurricularController.php');
    consultar_uc_data();
});

Router::get('a/unidades-curriculares/obtener_uc', function(){
    cargar_controlador('UnidadesCurriculares', 'unidadCurricularController.php');
    obtener_unidad_curricular();
});

Router::post('a/unidades-curriculares/actualizar', function(){
    cargar_controlador('UnidadesCurriculares', 'unidadCurricularController.php');
    actualizar_unidad_curricular();
});
