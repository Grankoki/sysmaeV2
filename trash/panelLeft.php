<div>
	 <?php
            foreach ($listaCursoDocente as $registro){              
                ?><a class="nav-link" href="?docente=cmd_cursosDocente&idSeccion=<?php echo base64_encode($registro['idSeccion'])?>
                					&idCurso=<?php echo base64_encode($registro['idCurso'])?>&detalleSeccion=<?php echo $registro['seccion']?>
                							&detalleCurso=<?php echo $registro['curso']?>"> <?php                    
                    echo $registro["seccion"]."<br>";
                	echo $registro["curso"]."<br>";                	
                ?></a> <?php
            }
            ?>
</div>