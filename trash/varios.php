<link rel="stylesheet" href="css/bootstrap.css">
<script src="js/jquery-3.3.1.slim.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>



<?php
if ($listaEvaluacionTUCE!=NULL)
{   date_default_timezone_set('America/Lima');
    $fechaHoy = date('Y-m-d H:i:s');
    foreach ($listaEvaluacionTUCE as $registro){
        $date1 = $registro->fechaTermino;
        $date2 = $registro->fechaInicio;
        $diferenciaFin = strtotime($date1) - strtotime($fechaHoy);
        $diferenciaInicio = strtotime($fechaHoy) - strtotime($date2);
        $rangoSegundos = strtotime($date1) - strtotime($date2);
        // echo "<br>Rango Seg: ".$rangoSegundos." <br>";
        // echo "<br>DiferenciaFin: ".$diferenciaFin." <br>";
        // echo "<br>DiferenciaInicio: ".$diferenciaInicio." <br>";
        if ($registro->estado==1){
            ?>
            <div class="row form-group">
                <?php

                if($diferenciaInicio>0 && $diferenciaInicio<=$rangoSegundos && 0 < $diferenciaFin){?>
                    <a class="nav-link" href="?estudiante=frm_desarrollarEvaluacion&idEvaluacion=<?php echo $registro->idEvaluacion ?>">
                        <?php echo $registro->titulo ?>
                    </a>
                <?php }else{  ?>
                    <a class="nav-link">
                        <?php echo $registro->titulo ?> <br><b>EVALUACIÓN CERRADA</b>
                    </a>
                <?php } ?>

            </div>
            <div class="row form-group">
                <div class="col-12">
                    <?php echo $registro->descripcion ?>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-4"><?php echo "Inicia  : ".$registro->fechaInicio ?></div>
                <div class="col-4"><?php echo "Finaliza: ".$registro->fechaTermino ?></div>
            </div>
            <?php
        }
    }
}?>








<div class="col-sm-2">
        <label for="cbx_tiempo">Límite tiempo:</label>
        <select name="cbx_tiempo" id="cbx_tiempo" class="form-control" aria-label="Default select example">
            <?php
            $i=10;
            while($i<=120){
                //
                echo "<option value=".$i.">".$i."</option>\n";
                if ($i>=60){
                    $i=$i+30;
                }else{ $i=$i+5; }
            }
            ?>
        </select>
</div>




<?php if(isset($_POST['cmdFinalizar'])){ ?>
    <form id="cmd_desarrollarEvaluacion" method="post">
        <div class="row form-group">
            <div class="col">
                RESULTADO DE LA EVALUACION
                <br>
                <?php
                echo "Arreglo Radio con los idPregunta <br>";
                $idPreguntaRd = $_POST['idPreguntaRd'];
                var_dump($idPreguntaRd);
                echo "<br>";
                echo "Arreglo Radio con los OPC seleccionados <br>";
                if (!isset($_POST['arraySelectRadio'])){
                    // $arraySelectRadio=[];
                    $cr=0;
                    foreach ($idPreguntaRd as $cRadio){
                        $arraySelectRadio[$cr]=''; $cr++;
                    }
                }else{
                    $cr=0;
                    $arraySelectRadioTmp = $_POST['arraySelectRadio'];
                    foreach ($idPreguntaRd as $cRadio){
                        if(!isset($arraySelectRadioTmp[$cr])){
                            $arraySelectRadio[$cr]='0';
                        }else { $arraySelectRadio[$cr]=$arraySelectRadioTmp[$cr]; }
                        $cr++;
                    }
                }
                var_dump($arraySelectRadio);


                echo "<br>";
                echo "Arreglo Checkboxs con los idPregunta <br>";
                $idPreguntaCk = $_POST['idPreguntaCk'];
                var_dump($idPreguntaCk);
                echo "<br>";
                echo "Arreglo Checkbox con los OPC seleccionados <br>";

                if (!isset($_POST['arraySelectCk'])){
                    echo "no selecciono ningun checkbox";
                    $arraySelectCk=[];
                    $ck=0;
                }else{
                    $ck=0;
                    $arraySelectCkTmp = $_POST['arraySelectCk'];
                    foreach ($idPreguntaCk as $cCheck){
                        $arraySelectCk[$ck][0]= $cCheck;  //  $idPreguntaCk[$ck];
                        for ($z=1; $z<3; $z++ ){
                            for ($y=0; $y<4; $y++){
                                if(isset($arraySelectCkTmp[$ck][$y])){
                                    $arraySelectCk[$ck][$z]=$arraySelectCkTmp[$ck][$y];
                                    $z++;
                                }
                            }
                        }
                        $ck++;
                    }
                    echo "<br> ----------------------- <br>";
                    echo('<pre>');
                    var_dump($arraySelectCk);
                    echo('</pre>');
                }


                ?>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-8">
                <?php
                $idPreguntaVF = $_POST['idPreguntaVF'];
                $opcVFSelected  = $_POST['cbx_respuesta']; //arreglo q trae las opciones Seleccionadas VF
                echo "<br> ------------------- <br>";
                echo "idPreguntaVF <br>";
                var_dump($idPreguntaVF);
                echo "<br> ------------------- <br>";
                var_dump($opcVFSelected);

                ?>
            </div>
        </div>
    </form>
<?php } ?>




public function InsertStudentAnswers($matrizRptaRadio, $matrizRptaCheck,$matrizRptaVF, $intentos, $idEvaluacion, $idEstudiante,$intentoLast){
try {
$ic = new Connection();
$puntajePrg=0; $puntajeTotal=0;
$intentos = $intentos - 1;
if($intentoLast==null){ $intento = 1; }else{ $intento = $intentoLast+1; }
if($matrizRptaRadio!=null){
$i=0;
foreach($matrizRptaRadio as $registro){
$puntajePrg=$matrizRptaRadio[$i][1]*$matrizRptaRadio[$i][2];
$puntajeTotal=$puntajeTotal+$puntajePrg;
$idPregunta = $matrizRptaRadio[$i][0];
$estado = 1;
$sql ="INSERT INTO tb_respuesta_estudiante (idEvaluacion, idEstudiante, estado, calificacion, idPregunta, intento) VALUES (?, ?, ?, ?, ?, ?)";
$insertar = $ic->db->prepare($sql);
$insertar->bindParam(1,$idEvaluacion);
$insertar->bindParam(2,$idEstudiante);
$insertar->bindParam(3,$estado);
$insertar->bindParam(4,$puntajePrg);
$insertar->bindParam(5,$idPregunta);
$insertar->bindParam(6,$intento);
$insertar->execute();
$this->idRespuestaEstudiante = $ic->db->lastInsertId();

$opcSeleccionada = $matrizRptaRadio[$i][3];
$sql ="INSERT INTO tb_respuesta_multiple (idRespuestaEstudiante, opcionSeleccionada, estado) VALUES (?, ?, ?)";
$insertar = $ic->db->prepare($sql);
$insertar->bindParam(1,$this->idRespuestaEstudiante);
$insertar->bindParam(2,$opcSeleccionada);
$insertar->bindParam(3,$estado);
$insertar->execute();
$i++;
}
}else {
echo "<br>no se han seleccionado opciones RADIO <br>";
}

if($matrizRptaCheck!=null) {
$i=0;
foreach($matrizRptaCheck as $registro){
$puntajePrg=$matrizRptaCheck[$i][1]*$matrizRptaCheck[$i][2];
$puntajeTotal=$puntajeTotal+$puntajePrg;
$idPregunta = $matrizRptaCheck[$i][0];
$estado = 1;
$sql ="INSERT INTO tb_respuesta_estudiante (idEvaluacion, idEstudiante, estado, calificacion, idPregunta, intento) VALUES (?, ?, ?, ?, ?, ?)";
$insertar = $ic->db->prepare($sql);
$insertar->bindParam(1,$idEvaluacion);
$insertar->bindParam(2,$idEstudiante);
$insertar->bindParam(3,$estado);
$insertar->bindParam(4,$puntajePrg);
$insertar->bindParam(5,$idPregunta);
$insertar->bindParam(6,$intento);
$insertar->execute();
$this->idRespuestaEstudiante = $ic->db->lastInsertId();

$opcSeleccionada = $matrizRptaCheck[$i][3];
$sql ="INSERT INTO tb_respuesta_multiple (idRespuestaEstudiante, opcionSeleccionada, estado) VALUES (?, ?, ?)";
$insertar = $ic->db->prepare($sql);
$insertar->bindParam(1,$this->idRespuestaEstudiante);
$insertar->bindParam(2,$opcSeleccionada);
$insertar->bindParam(3,$estado);
$insertar->execute();
$i++;
}
}else {
echo "<br>no se han seleccionado opciones Checkbox <br>";
}

if($matrizRptaVF!=null) {
$i=0;
foreach($matrizRptaVF as $registro){
$puntajePrg=$matrizRptaVF[$i][1];
$idPregunta = $matrizRptaVF[$i][0];
$puntajeTotal=$puntajeTotal+$puntajePrg;
$estado = 1;
$sql ="INSERT INTO tb_respuesta_estudiante (idEvaluacion, idEstudiante, estado, calificacion, idPregunta, intento) VALUES (?, ?, ?, ?, ?, ?)";
$insertar = $ic->db->prepare($sql);
$insertar->bindParam(1,$idEvaluacion);
$insertar->bindParam(2,$idEstudiante);
$insertar->bindParam(3,$estado);
$insertar->bindParam(4,$puntajePrg);
$insertar->bindParam(5,$idPregunta);
$insertar->bindParam(6,$intento);
$insertar->execute();
$this->idRespuestaEstudiante = $ic->db->lastInsertId();

$opcSeleccionada = $matrizRptaVF[$i][2];
$sql ="INSERT INTO tb_respuesta_vf (idRespuestaEstudiante, opcionSeleccionada, estado) VALUES (?, ?, ?)";
$insertar = $ic->db->prepare($sql);
$insertar->bindParam(1,$this->idRespuestaEstudiante);
$insertar->bindParam(2,$opcSeleccionada);
$insertar->bindParam(3,$estado);
$insertar->execute();
$i++;
}
}else {
echo "<br>no se han seleccionado opciones V F <br>";
}
date_default_timezone_set('America/Lima');
$fechaHoy       = date('Y-m-d H:i:s');
$sql = "UPDATE tb_evaluacion_estudiante set puntajeTotal='$puntajeTotal', fechaRegistro='$fechaHoy', intentos='$intentos' where idEvaluacion='$idEvaluacion' and idEstudiante='$idEstudiante'";
$actualizar = $ic->db->prepare($sql);
$actualizar->execute();
} catch (PDOException $e) {
echo "Error -->: ".$e->getMessage();
}
return $puntajeTotal;
}




