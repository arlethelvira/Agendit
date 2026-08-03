<!doctype html>
<html lang="es">

<head>

    <?= $this->element('title-meta', ['title' => 'Registro Especialista']) ?>

    <?= $this->element('head-css') ?>

</head>


<body class="authentication-bg">


<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">

<div class="container">


<div class="row justify-content-center">


<div class="col-xl-5">


<div class="card auth-card">


<div class="card-body px-3 py-5">


<!-- Logo -->
<div class="mx-auto mb-4 text-center auth-logo">


<a href="/" class="logo-dark">

<img src="/images/logoAgendit.png"
height="30"
alt="logo">

</a>


<a href="/" class="logo-light">

<img src="/images/letrasAgendit.png"
height="30"
alt="logo">

</a>


</div>



<h2 class="fw-bold text-center fs-18">

Únete a Agendit como Especialista

</h2>


<p class="text-muted text-center mt-1 mb-4">

Crea tu perfil profesional y conecta con tus socios.

</p>




<div class="px-4">


<?= $this->Form->create($usuario, [

'class'=>'authentication-form'

]) ?>





<!-- Nombre -->

<div class="mb-3">

<label class="form-label">

Nombre

</label>


<?= $this->Form->control('nombre',[

'label'=>false,

'class'=>'form-control',

'placeholder'=>'Ingresa tu nombre'

]) ?>


</div>






<!-- Apellido paterno -->

<div class="mb-3">

<label class="form-label">

Apellido paterno

</label>


<?= $this->Form->control('apellido_paterno',[

'label'=>false,

'class'=>'form-control',

'placeholder'=>'Apellido paterno'

]) ?>


</div>





<!-- Apellido materno -->

<div class="mb-3">

<label class="form-label">

Apellido materno

</label>


<?= $this->Form->control('apellido_materno',[

'label'=>false,

'class'=>'form-control',

'placeholder'=>'Apellido materno'

]) ?>


</div>







<!-- Email -->

<div class="mb-3">

<label class="form-label">

Correo electrónico

</label>


<?= $this->Form->control('email',[

'label'=>false,

'class'=>'form-control',

'placeholder'=>'correo@ejemplo.com'

]) ?>


</div>






<!-- Contraseña -->

<div class="mb-3">

<label class="form-label">

Contraseña

</label>


<?= $this->Form->control('contrasena',[

'type'=>'password',

'label'=>false,

'class'=>'form-control',

'placeholder'=>'Crea una contraseña'

]) ?>


</div>








<!-- Tipo especialista -->

<div class="mb-3">

<label class="form-label">

Tipo de especialista

</label>


<?= $this->Form->control('id_tipo',[

'type'=>'select',

'label'=>false,

'class'=>'form-select',

'options'=>$tiposEspecialista,

'empty'=>'Selecciona una opción'

]) ?>


</div>








<!-- Cedula -->

<div class="mb-4">

<label class="form-label">

Cédula profesional

</label>


<?= $this->Form->control('cedula_profesional',[

'label'=>false,

'class'=>'form-control',

'placeholder'=>'Ingresa tu cédula profesional'

]) ?>


</div>








<div class="d-grid">


<?= $this->Form->button(

'Crear cuenta especialista',

[

'class'=>'btn btn-primary'

]

) ?>


</div>




<?= $this->Form->end() ?>



</div>



</div>

</div>

</div>





<p class="text-white text-center mt-3">

¿Ya tienes una cuenta?


<a href="/users/login"

class="text-white fw-bold">

Iniciar sesión

</a>


</p>




<p class="text-white text-center">


¿Eres usuario?


<a href="/users/register"

class="text-white fw-bold">

Crear cuenta

</a>


</p>




</div>

</div>

</div>




<?= $this->element('vendor-scripts') ?>


</body>

</html>