 <script>
   $(document).ready(function() {
     $('#no_registro').click(function() {
       var tabLink = document.getElementById('tab-register');
       var tab = new bootstrap.Tab(tabLink);
       tab.show();
     });
   });

   function opcionesFuncionalidad(accion) {
     switch (accion) {
       case 'registrarse':
         //VARIABLES A VALIDAR  
         var nombre = $('#inp_nombre').val();
         var telefono = $('#inp_telefono').val();
         var email = $('#inp_email').val();
         var interes = $('#inp_interes').val();
         var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

         if (nombre != '' && telefono != '' && email != '') {
           if (!emailRegex.test(email)) {
             return;
           }
           if (telefono.length != 10) {
             return;
           }
           toastr.info('<i class="fa fa-spinner fa-spin"></i> Procesando...', 'Cargando', {
             timeOut: 0,
             extendedTimeOut: 0,
           });
           $.ajax({
             url: 'index.php?c=registro&m=crearUsuarioNotificaciones',
             type: 'POST',
             dataType: 'text',
             data: {
               nombres: nombre.trim(),
               telefono: telefono.trim(),
               email: email.trim(),
               interes: interes?.trim() || ''
             },
             success: function(r) {
               toastr.clear(); // Limpiar el loading
               var respuesta = JSON.parse(r);
               if (respuesta.error) {
                 toastr.error('Ocurrió un error al registrar', 'Error');
               } else {
                 if (respuesta.existencia) {
                   toastr.info('Ya se encuentra registrado con su: ' + respuesta.resultado[0]['tipo_coincidencia'] + '', 'Información');
                 } else if (respuesta.insert) {
                   $('#inp_nombre').val('');
                   $('#inp_telefono').val('');
                   $('#inp_email').val('');
                   $('#inp_interes').val('');
                   toastr.success('Se registro de manera éxitosa, se notificara cualquier novedad', 'Éxito');
                 }
               }
             },
             error: function(r, s, errorThrown) {
               toastr.clear(); // Limpiar el loading
               toastr.error('Error de conexión al servidor', 'Error');
               console.error('Error al registrar usuario:', {
                 xhr,
                 status,
                 error
               });
             }
           });
         }
         break;
       case 'crearCuenta':
         //VARIABLES A VALIDAR  
         var nombre = $('#r_nombres').val();
         var telefono = $('#r_telefono').val();
         var email = $('#r_email').val();
         var contra = $('#r_password').val();
         var v_contra = $('#r_confirm_password').val();
         var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

         if (nombre != '' && telefono != '' && email != '' && contra != '' && v_contra != '') {
           if (!emailRegex.test(email)) {
             return;
           }
           if (telefono.length != 10) {
             return;
           }
           if (contra.length < 8) {
             return;
           }
           if (contra != v_contra) {
             toastr.warning('Las contraseñas deben coincidir', 'Alerta');
             return;
           }
           toastr.info('<i class="fa fa-spinner fa-spin"></i> Procesando...', 'Cargando', {
             timeOut: 0,
             extendedTimeOut: 0,
           });

           $.ajax({
             url: 'index.php?c=registro&m=crearUsuario',
             type: 'POST',
             dataType: 'text',
             data: {
               nombres: nombre.trim(),
               telefono: telefono.trim(),
               email: email.trim(),
               password: v_contra.trim()
             },
             success: function(r) {
               toastr.clear();
               var respuesta = JSON.parse(r);
               if (respuesta.error) {
                 toastr.error('Ocurrió un error al registrar', 'Error');
               } else {
                 if (respuesta.existencia) {
                   toastr.info('Existe una cuenta con el mismo correo eletrónico', 'Información');
                 } else if (respuesta.insert) {
                  toastr.success('Se registro de manera éxitosa, ya puedes ingresar', 'Éxito');
                   $('#r_nombres').val('');
                   $('#r_telefono').val('');
                   $('#r_email').val('');
                   $('#r_password').val('');
                   $('#r_confirm_password').val('');
                   var tabLink = document.getElementById('tab-login');
                   var tab = new bootstrap.Tab(tabLink);
                   tab.show();
                  
                 }
               }
             },
             error: function(r, s, errorThrown) {
               toastr.clear(); // Limpiar el loading
               toastr.error('Error de conexión al servidor', 'Error');
               console.error('Error al registrar usuario:', {
                 xhr,
                 status,
                 error
               });
             }
           });
         }
         break;
       case 'ingresar':
         //VARIABLES A VALIDAR  
         var email = $('#v_correo').val();
         var contra = $('#v_contra').val();

         var emailRegex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

         if (email != '' && contra != '') {
           if (!emailRegex.test(email)) {
             return;
           }
           if (contra.length < 8) {
             return;
           }
           toastr.info('<i class="fa fa-spinner fa-spin"></i> Procesando...', 'Cargando', {
             timeOut: 0,
             extendedTimeOut: 0,
           });

           $.ajax({
             url: 'index.php?c=registro&m=ingresar',
             type: 'POST',
             dataType: 'text',
             data: {
               email: email.trim(),
               password: contra.trim()
             },
             success: function(r) {
               toastr.clear();
               var respuesta = JSON.parse(r);
               if (respuesta.error) {
                 toastr.error('Ocurrió un error al ingresar', 'Error');
               } else {
                 if (respuesta.ingreso) {
                   toastr.success('Se inicio de manera correcta', 'Éxito');
                   window.location.href = 'index.php?c=paginas&m=home';
                 } else {
                   toastr.error('Correo electrónico o contraseña incorrecta', 'Error');
                 }
               }
             },
             error: function(r, s, errorThrown) {
               toastr.clear(); // Limpiar el loading
               toastr.error('Error de conexión al servidor', 'Error');
               console.error('Error al registrar usuario:', {
                 xhr,
                 status,
                 error
               });
             }
           });
         }
         break;
         case 'cerrarSession':
            window.location.href = "index.php?c=paginas&m=logout";
          break;
       default:
         break;
     }
   }

   function verPassword() {
     var passwordField = document.getElementById('r_password');
     var eyeIcon = document.getElementById('eye-icon');
     if (passwordField.type === "password") {
       passwordField.type = "text"; // Muestra la contraseña
       eyeIcon.classList.remove("bi-eye-slash");
       eyeIcon.classList.add("bi-eye");
     } else {
       passwordField.type = "password"; // Oculta la contraseña
       eyeIcon.classList.remove("bi-eye");
       eyeIcon.classList.add("bi-eye-slash");
     }
   }

   // Función para alternar la visibilidad de la confirmación de contraseña
   function verConfirmPassword() {
     var confirmPasswordField = document.getElementById('r_confirm_password');
     var confirmEyeIcon = document.getElementById('confirm-eye-icon');
     if (confirmPasswordField.type === "password") {
       confirmPasswordField.type = "text"; // Muestra la confirmación de contraseña
       confirmEyeIcon.classList.remove("bi-eye-slash");
       confirmEyeIcon.classList.add("bi-eye");
     } else {
       confirmPasswordField.type = "password"; // Oculta la confirmación de contraseña
       confirmEyeIcon.classList.remove("bi-eye");
       confirmEyeIcon.classList.add("bi-eye-slash");
     }
   }
 </script>
 <style>
   .flipbook {
     display: flex;
     flex-wrap: nowrap;
   }

   .flipbook div {
     width: 100%;
     height: 100%;
     background-position: center;
     background-repeat: no-repeat;
   }

   .zoomed {
     transform: scale(2);
     transition: transform 0.3s ease;
   }
 </style>

 <body class="index-page">
   <header
     id="header"
     class="header d-flex flex-column justify-content-center">
     <i class="header-toggle d-xl-none bi bi-list"></i>

     <nav id="navmenu" class="navmenu">
       <ul>
         <li>
           <a href="#hero" class="active"><i class="bi bi-book-half"></i><span>Revista</span></a>
         </li>
         <li>
           <a href="#contact"><i class="bi bi-newspaper"></i><span>Únete</span></a>
         </li>
         <li>
           <a href="#compra"><i class="bi bi-bag-plus"></i><span>Comprar</span></a>
         </li>
         <li>
           <a href="#services"><i class="bi bi-mic"></i><span>Podcast</span></a>
         </li>
         <?php if (!isset($_SESSION["usuario"])) { ?>
           <li>
             <a href="#session"><i class="bi bi-person"></i><span>Ingresar</span></a>
           </li>
         <?php } else {?>
           <a href="#" onclick="opcionesFuncionalidad('cerrarSession')"><i class="bi bi-box-arrow-left"></i><span>Salir</span></a>
         <?php } ?>
       </ul>
     </nav>
   </header>
   <!--REVISTA-->
   <section id="hero" class="hero section light-background">
     <div class="container" data-aos="zoom-out">
       <div class="row justify-content-center text-center">
         <div class="col-lg-12">
           <h2>Encontrado Lideres</h2>
           <p>Se parte de la <span class="typed" data-typed-items="Evolución, Inspiración, Emprendimiento, Motivación">Visionarismo</span><span class="typed-cursor typed-cursor--blink" aria-hidden="true"></span></p>
         </div>
       </div>
     </div>
     <div id="canvas" style="position: relative;bottom:150px">

       <div class="zoom-icon zoom-icon-in" onclick="toggleZoomIcon()"><i class="bi bi-zoom-in"></i></div>

       <div class="magazine-viewport">
         <div class="container">
           <div class="magazine">
             <div ignore="1" class="next-button"></div>
             <div ignore="1" class="previous-button"></div>
           </div>
         </div>
       </div>

       <div class="thumbnails">
         <div>
           <ul>
             <li class="i">
               <img src="pages/1-thumb.jpg" width="76" height="100" class="page-1">
               <span>1</span>
             </li>
             <li class="d">
               <img src="pages/2-thumb.jpg" width="76" height="100" class="page-2">
               <img src="pages/3-thumb.jpg" width="76" height="100" class="page-3">
               <span>2-3</span>
             </li>
             <li class="d">
               <img src="pages/4-thumb.jpg" width="76" height="100" class="page-4">
               <img src="pages/5-thumb.jpg" width="76" height="100" class="page-5">
               <span>4-5</span>
             </li>
             <li class="d">
               <img src="pages/6-thumb.jpg" width="76" height="100" class="page-6">
               <img src="pages/7-thumb.jpg" width="76" height="100" class="page-7">
               <span>6-7</span>
             </li>
             <li class="d">
               <img src="pages/8-thumb.jpg" width="76" height="100" class="page-8">
               <img src="pages/9-thumb.jpg" width="76" height="100" class="page-9">
               <span>8-9</span>
             </li>
             <li class="d">
               <img src="pages/10-thumb.jpg" width="76" height="100" class="page-10">
               <img src="pages/11-thumb.jpg" width="76" height="100" class="page-11">
               <span>10-11</span>
             </li>
             <li class="i">
               <img src="pages/12-thumb.jpg" width="76" height="100" class="page-12">
               <span>12</span>
             </li>
           </ul>
         </div>
       </div>
     </div>
   </section>
   <main class="main">
     <!--Registro-->
     <section id="contact" class="contact section">
       <div class="container section-title" data-aos="fade-up">
         <h2>Registrate</h2>
         <p>
           Te notificaremos las nuevas Ediciónes de nuestra revista
         </p>
       </div>
       <div class="container" data-aos="fade" data-aos-delay="100">
         <div class="row gy-4">
           <div class="col-lg-4">
             <div
               class="info-item d-flex"
               data-aos="fade-up"
               data-aos-delay="200">
               <i class="bi bi-envelope-exclamation"></i>
               <div>
                 <h3>Acceso Antisipado</h3>
                 <p>Exclusividad a la salida, antes de que se publique.</p>
               </div>
             </div>
             <div
               class="info-item d-flex"
               data-aos="fade-up"
               data-aos-delay="300">
               <i class="bi bi-file-earmark-bar-graph"></i>
               <div>
                 <h3>Descuentos y Ofertas Especiales</h3>
                 <p>Descuentos exclusivos y personalizados.</p>
               </div>
             </div>
             <div
               class="info-item d-flex"
               data-aos="fade-up"
               data-aos-delay="400">
               <i class="bi bi-piggy-bank"></i>
               <div>
                 <h3>Recompensas o Puntos por Registro</h3>
                 <p>Gana puntos por registro y recibe la revista fisica gratis.</p>
               </div>
             </div>
           </div>

           <div class="col-lg-8">
             <form
               class="php-email-form"
               data-aos="fade-up"
               data-aos-delay="200">
               <div class="row gy-4">
                 <div class="col-md-6">
                   <input
                     type="text"
                     name="name"
                     class="form-control"
                     placeholder="Nombre completo"
                     required=""
                     id="inp_nombre" />
                 </div>
                 <div class="col-md-6">
                   <input
                     type="tel"
                     class="form-control"
                     name="telefono"
                     maxlength="10"
                     placeholder="Teléfono (10 dígitos)"
                     required
                     pattern="[0-9]{10}"
                     title="Debe contener 10 dígitos numéricos"
                     id="inp_telefono" />
                 </div>
                 <div class="col-md-12">
                   <input
                     type="email"
                     class="form-control"
                     name="email"
                     placeholder="Correo electrónico"
                     required=""
                     id="inp_email" />
                 </div>
                 <div class="col-md-12">
                   <textarea class="form-control" name="message" rows="6" placeholder="¿Porque te interesa? (opcional)" id="inp_interes"></textarea>
                 </div>

                 <div class="col-md-12 text-center">
                   <button type="submit" onclick="opcionesFuncionalidad('registrarse')">Registrase</button>
                 </div>
               </div>
             </form>
           </div>
         </div>
       </div>
     </section>

     <!-- Datos Registro -->
     <section id="stats" class="stats section">
       <div class="container" data-aos="fade-up" data-aos-delay="100">
         <div class="row gy-4">
           <div
             class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
             <i class="bi bi-emoji-smile"></i>
             <div class="stats-item">
               <span
                 data-purecounter-start="0"
                 data-purecounter-end="232"
                 data-purecounter-duration="1"
                 class="purecounter"></span>
               <p>Vistas</p>
             </div>
           </div>

           <div
             class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
             <i class="bi bi-journal-richtext"></i>
             <div class="stats-item">
               <span
                 data-purecounter-start="0"
                 data-purecounter-end="521"
                 data-purecounter-duration="1"
                 class="purecounter"></span>
               <p>Proyectos</p>
             </div>
           </div>

           <div
             class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
             <i class="bi bi-headset"></i>
             <div class="stats-item">
               <span
                 data-purecounter-start="0"
                 data-purecounter-end="1463"
                 data-purecounter-duration="1"
                 class="purecounter"></span>
               <p>Podcast</p>
             </div>
           </div>

           <div
             class="col-lg-3 col-md-6 d-flex flex-column align-items-center">
             <i class="bi bi-people"></i>
             <div class="stats-item">
               <span
                 data-purecounter-start="0"
                 data-purecounter-end="15"
                 data-purecounter-duration="1"
                 class="purecounter"></span>
               <p>Somos</p>
             </div>
           </div>
         </div>
       </div>
     </section>

     <!--COMPRAR-->
     <section id="compra" class="compra section">
       <div class="container section-title" data-aos="fade-up">
         <h2>Impulsa tu conocimiento y liderazgo con nuestra revista</h2>
         <p>
           Explora las últimas tendencias en tecnología y estrategias de liderazgo que están moldeando el futuro. Cada edición está diseñada para ofrecerte información valiosa, análisis profundos y consejos prácticos de expertos. ¡No te quedes atrás, compra tu ejemplar y lleva tu carrera al siguiente nivel!
         </p>
       </div>

       <div class="container">
         <div
           class="isotope-layout"
           data-default-filter="*"
           data-layout="masonry"
           data-sort="original-order">
           <ul
             class="compra-filters isotope-filters"
             data-aos="fade-up"
             data-aos-delay="100">
             <li data-filter="*" class="filter-active">Todas</li>
             <li data-filter=".filter-app">Nueva</li>
             <li data-filter=".filter-product">Antigua</li>
           </ul>

           <div
             class="row gy-4 isotope-container"
             data-aos="fade-up"
             data-aos-delay="200">
             <div
               class="col-lg-4 col-md-6 compra-item isotope-item filter-app">
               <img
                 src="pages/1-large.jpg"
                 class="img-fluid"
                 alt="" />
               <div class="compra-info">
                 <h4>Primera Edición</h4>
                 <p>¿Se acerca el acoso de los gigantes?</p>
                 <a
                   href="pages/1-large.jpg"
                   title="Primera Edición"
                   data-gallery="compra-gallery-app"
                   class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                 <a
                   href="compra-details.html"
                   title="Agregar carrito"
                   class="details-link"><i class="bi bi-bag-plus"></i></a>
               </div>
             </div>
           </div>
         </div>
     </section>

     <!-- PODCAST -->
     <section id="services" class="services section">
       <div class="container section-title" data-aos="fade-up">
         <h2>Podcast</h2>
         <p>
           Te invitamos a unirte a nuestra comunidad donde exploramos Liderazgo y innovación con episodios llenos de inspiración, aprendizaje y entretenimiento.
         </p>
         <div class="social-links d-flex justify-content-center py-3" style="font-size: 40px;">
           <a href=""><i class="bi bi-youtube"></i></a>
           <a href=""><i class="bi bi-spotify"></i></a>
           <a href=""><i class="bi bi-facebook"></i></a>
           <a href=""><i class="bi bi-instagram"></i></a>
         </div>
         <div class="container">
           <div class="row">
             <div
               class="col-lg-4 col-md-6"
               data-aos="fade-up"
               data-aos-delay="100">
               <div class="service-item item-cyan position-relative">
                 <div class="icon">
                   <svg
                     width="100"
                     height="100"
                     viewBox="0 0 600 600"
                     xmlns="http://www.w3.org/2000/svg">
                     <path
                       stroke="none"
                       stroke-width="0"
                       d="M300,521.0016835830174C376.1290562159157,517.8887921683347,466.0731472004068,529.7835943286574,510.70327084640275,468.03025145048787C554.3714126377745,407.6079735673963,508.03601936045806,328.9844924480964,491.2728898941984,256.3432110539036C474.5976632858925,184.082847569629,479.9380746630129,96.60480741107993,416.23090153303,58.64404602377083C348.86323505073057,18.502131276798302,261.93793281208167,40.57373210992963,193.5410806939664,78.93577620505333C130.42746243093433,114.334589627462,98.30271207620316,179.96522072025542,76.75703585869454,249.04625023123273C51.97151888228291,328.5150500222984,13.704378332031375,421.85034740162234,66.52175969318436,486.19268352777647C119.04800174914682,550.1803526380478,217.28368757567262,524.383925680826,300,521.0016835830174"></path>
                   </svg>
                   <i class="bi bi-activity"></i>
                 </div>
                 <a href="#" class="stretched-link">
                   <h3>Encuentra el sentido del liderazgo en tiempos de cambio.</h3>
                 </a>
                 <p>
                   En "Encontrando Sentido", no solo hablamos de liderazgo, sino que exploramos cómo los líderes pueden encontrar propósito en un mundo en constante transformación.
                 </p>
               </div>
             </div>

             <div
               class="col-lg-4 col-md-6"
               data-aos="fade-up"
               data-aos-delay="200">
               <div class="service-item item-orange position-relative">
                 <div class="icon">
                   <svg
                     width="100"
                     height="100"
                     viewBox="0 0 600 600"
                     xmlns="http://www.w3.org/2000/svg">
                     <path
                       stroke="none"
                       stroke-width="0"
                       fill="#f5f5f5"
                       d="M300,582.0697525312426C382.5290701553225,586.8405444964366,449.9789794690241,525.3245884688669,502.5850820975895,461.55621195738473C556.606425686781,396.0723002908107,615.8543463187945,314.28637112970534,586.6730223649479,234.56875336149918C558.9533121215079,158.8439757836574,454.9685369536778,164.00468322053177,381.49747125262974,130.76875717737553C312.15926192815925,99.40240125094834,248.97055460311594,18.661163978235184,179.8680185752513,50.54337015887873C110.5421016452524,82.52863877960104,119.82277516462835,180.83849132639028,109.12597500060166,256.43424936330496C100.08760227029461,320.3096726198365,92.17705696193138,384.0621239912766,124.79988738764834,439.7174275375508C164.83382741302287,508.01625554203684,220.96474134820875,577.5009287672846,300,582.0697525312426"></path>
                   </svg>
                   <i class="bi bi-broadcast"></i>
                 </div>
                 <a href="#" class="stretched-link">
                   <h3>La innovación no solo se trata de tecnología, sino de cómo da sentido al mundo.</h3>
                 </a>
                 <p>
                   A través de nuestras conversaciones, descubrirás cómo la innovación puede ir más allá de los avances tecnológicos y convertirse en una herramienta para resolver problemas sociales, económicos y ambientales.
                 </p>
               </div>
             </div>

             <div
               class="col-lg-4 col-md-6"
               data-aos="fade-up"
               data-aos-delay="300">
               <div class="service-item item-teal position-relative">
                 <div class="icon">
                   <svg
                     width="100"
                     height="100"
                     viewBox="0 0 600 600"
                     xmlns="http://www.w3.org/2000/svg">
                     <path
                       stroke="none"
                       stroke-width="0"
                       fill="#f5f5f5"
                       d="M300,541.5067337569781C382.14930387511276,545.0595476570109,479.8736841581634,548.3450877840088,526.4010558755058,480.5488172755941C571.5218469581645,414.80211281144784,517.5187510058486,332.0715597781072,496.52539010469104,255.14436215662573C477.37192572678356,184.95920475031193,473.57363656557914,105.61284051026155,413.0603344069578,65.22779650032875C343.27470386102294,18.654635553484475,251.2091493199835,5.337323636656869,175.0934190732945,40.62881213300186C97.87086631185822,76.43348514350839,51.98124368387456,156.15599469081315,36.44837278890362,239.84606092416172C21.716077023791087,319.22268207091537,43.775223500013084,401.1760424656574,96.891909868211,461.97329694683043C147.22146801428983,519.5804099606455,223.5754009179313,538.201503339737,300,541.5067337569781"></path>
                   </svg>
                   <i class="bi bi-easel"></i>
                 </div>
                 <a href="#" class="stretched-link">
                   <h3>Escucha a los líderes que están redefiniendo el sentido del éxito.</h3>
                 </a>
                 <p>
                   En cada episodio de "Encontrando Sentido", entrevistamos a personas influyentes que están rompiendo moldes en sus respectivos campos, desde la tecnología hasta el liderazgo social.
                 </p>
               </div>
             </div>

             <div
               class="col-lg-4 col-md-6"
               data-aos="fade-up"
               data-aos-delay="400">
               <div class="service-item item-red position-relative">
                 <div class="icon">
                   <svg
                     width="100"
                     height="100"
                     viewBox="0 0 600 600"
                     xmlns="http://www.w3.org/2000/svg">
                     <path
                       stroke="none"
                       stroke-width="0"
                       fill="#f5f5f5"
                       d="M300,503.46388370962813C374.79870501325706,506.71871716319447,464.8034551963731,527.1746412648533,510.4981551193396,467.86667711651364C555.9287308511215,408.9015244558933,512.6030010748507,327.5744911775523,490.211057578863,256.5855673507754C471.097692560561,195.9906835881958,447.69079081568157,138.11976852964426,395.19560036434837,102.3242989838813C329.3053358748298,57.3949838291264,248.02791733380457,8.279543830951368,175.87071277845988,42.242879143198664C103.41431057327972,76.34704239035025,93.79494320519305,170.9812938413882,81.28167332365135,250.07896920659033C70.17666984294237,320.27484674793965,64.84698225790005,396.69656628748305,111.28512138212992,450.4950937839243C156.20124167950087,502.5303643271138,231.32542653798444,500.4755392045468,300,503.46388370962813"></path>
                   </svg>
                   <i class="bi bi-bounding-box-circles"></i>
                 </div>
                 <a href="#" class="stretched-link">
                   <h3>Liderazgo con propósito, innovación con visión.</h3>
                 </a>
                 <p>
                   Te ofrece herramientas y perspectivas que puedes integrar directamente en tu vida profesional y personal para ser un líder más consciente, ético y visionario.
                 </p>
                 <a href="#" class="stretched-link"></a>
               </div>
             </div>

             <div
               class="col-lg-4 col-md-6"
               data-aos="fade-up"
               data-aos-delay="500">
               <div class="service-item item-indigo position-relative">
                 <div class="icon">
                   <svg
                     width="100"
                     height="100"
                     viewBox="0 0 600 600"
                     xmlns="http://www.w3.org/2000/svg">
                     <path
                       stroke="none"
                       stroke-width="0"
                       fill="#f5f5f5"
                       d="M300,532.3542879108572C369.38199826031484,532.3153073249985,429.10787420159085,491.63046689027357,474.5244479745417,439.17860296908856C522.8885846962883,383.3225815378663,569.1668002868075,314.3205725914397,550.7432151929288,242.7694973846089C532.6665558377875,172.5657663291529,456.2379748765914,142.6223662098291,390.3689995646985,112.34683881706744C326.66090330228417,83.06452184765237,258.84405631176094,53.51806209861945,193.32584062364296,78.48882559362697C121.61183558270385,105.82097193414197,62.805066853699245,167.19869350419734,48.57481801355237,242.6138429142374C34.843463184063346,315.3850353017275,76.69343916112496,383.4422959591041,125.22947124332185,439.3748458443577C170.7312796277747,491.8107796887764,230.57421082200815,532.3932930995766,300,532.3542879108572"></path>
                   </svg>
                   <i class="bi bi-calendar4-week icon"></i>
                 </div>
                 <a href="#" class="stretched-link">
                   <h3>El cambio comienza con una conversación</h3>
                 </a>
                 <p>
                   "Encontrando Sentido" es más que un podcast: es una invitación a participar en una conversación que da forma al futuro.
                 </p>
                 <a href="#" class="stretched-link"></a>
               </div>
             </div>

             <div
               class="col-lg-4 col-md-6"
               data-aos="fade-up"
               data-aos-delay="600">
               <div class="service-item item-pink position-relative">
                 <div class="icon">
                   <svg
                     width="100"
                     height="100"
                     viewBox="0 0 600 600"
                     xmlns="http://www.w3.org/2000/svg">
                     <path
                       stroke="none"
                       stroke-width="0"
                       fill="#f5f5f5"
                       d="M300,566.797414625762C385.7384707136149,576.1784315230908,478.7894351017131,552.8928747891023,531.9192734346935,484.94944893311C584.6109503024035,417.5663521118492,582.489472248146,322.67544863468447,553.9536738515405,242.03673114598146C529.1557734026468,171.96086150256528,465.24506316201064,127.66468636344209,395.9583748389544,100.7403814666027C334.2173773831606,76.7482773500951,269.4350130405921,84.62216499799875,207.1952322260088,107.2889140133804C132.92018162631612,134.33871894543012,41.79353780512637,160.00259165414826,22.644507872594943,236.69541883565114C3.319112789854554,314.0945973066697,72.72355303640163,379.243833228382,124.04198916343866,440.3218312028393C172.9286146004772,498.5055451809895,224.45579914871206,558.5317968840102,300,566.797414625762"></path>
                   </svg>
                   <i class="bi bi-chat-square-text"></i>
                 </div>
                 <a href="#" class="stretched-link">
                   <h3>El verdadero liderazgo se prueba en tiempos de adversidad.</h3>
                 </a>
                 <p>
                   Exploramos historias reales de líderes que han superado grandes obstáculos y transformado sus adversidades en oportunidades.
                 </p>
                 <a href="#" class="stretched-link"></a>
               </div>
             </div>
           </div>
         </div>
       </div>

     </section>

     <?php if (!isset($_SESSION["usuario"])) { ?>
       <!-- PODCAST -->
       <section id="session" class="services section">
         <div class="container section-title" data-aos="fade-up">
           <h2>Podcast</h2>
           <p>
             Te invitamos a unirte a nuestra comunidad donde exploramos Liderazgo y innovación con episodios llenos de inspiración, aprendizaje y entretenimiento.
           </p>
         </div>

         <div class="container">
           <div class="row justify-content-center">
             <div class="col-12 col-md-5 container_login">
               <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
                 <li class="nav-item" role="presentation">
                   <a class="nav-link active" id="tab-login" data-bs-toggle="pill" href="#pills-login" role="tab" aria-controls="pills-login" aria-selected="true">Ingresar</a>
                 </li>
                 <li class="nav-item" role="presentation">
                   <a class="nav-link" id="tab-register" data-bs-toggle="pill" href="#pills-register" role="tab" aria-controls="pills-register" aria-selected="false">Registrarse</a>
                 </li>
               </ul>

               <div class="tab-content">
                 <!-- Formulario Login -->
                 <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                   <form class="php-email-form" data-aos="fade-up" data-aos-delay="200" method="POST" action="submit_form.php">
                     <div class="text-center mb-3">
                       <p>Ingresar con:</p>
                       <button type="button" class="btn btn-danger btn-block mb-4" style="width: 100%;">
                         <i class="bi bi-google"></i> Ingresar con Google
                       </button>
                     </div>

                     <p class="text-center">o:</p>

                     <!-- Correo -->
                     <div class="form-outline mb-4">
                       <div class="input-group flex-nowrap">
                         <span class="input-group-text" id="addon-wrapping">@</span>
                         <input
                           id="v_correo"
                           type="email"
                           class="form-control"
                           placeholder="Correo Electrónico"
                           aria-label="Correo Electrónico"
                           aria-describedby="addon-wrapping"
                           required>
                       </div>
                     </div>

                     <!-- Contraseña -->
                     <div class="form-outline mb-4">
                       <div class="input-group flex-nowrap">
                         <span class="input-group-text" id="addon-wrapping"><i class="bi bi-lock"></i></span>
                         <input
                           id="v_contra"
                           type="password"
                           minlength="8"
                           class="form-control"
                           placeholder="Contraseña"
                           aria-label="Contraseña"
                           aria-describedby="addon-wrapping"
                           required>
                       </div>
                     </div>

                     <div class="row mb-4">
                       <div class="col-md-6 d-flex justify-content-center">
                         <div class="form-check mb-3 mb-md-0">
                           <!-- Aquí podrías agregar la opción de recordar sesión -->
                         </div>
                       </div>

                       <div class="col-md-6 d-flex justify-content-center">
                         <a href="#!">¿Olvidaste tu contraseña?</a>
                       </div>
                     </div>

                     <!-- Botón de Ingreso -->
                     <button type="submit" class="btn btn-dark btn-block mb-4" style="width: 100%;" onclick="opcionesFuncionalidad('ingresar')">Ingresar</button>

                     <div class="text-center">
                       <p>No tienes cuenta?
                         <a id="no_registro" style="cursor: pointer;color:#d47f3c">Registrar</a>
                       </p>
                     </div>
                   </form>

                 </div>

                 <!-- Formulario Registro -->
                 <div class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
                   <form>
                     <div class="text-center mb-3">
                       <p>Ingresar con:</p>
                       <button type="button" class="btn btn-danger btn-block mb-4" style="width: 100%;">
                         <i class="bi bi-google"></i> Ingresar con Google
                       </button>
                     </div>

                     <p class="text-center">o:</p>

                     <!-- Nombre completo -->
                     <div class="form-outline mb-4">
                       <div class="input-group flex-nowrap">
                         <span class="input-group-text" id="addon-wrapping"><i class="bi bi-person-fill"></i></span>
                         <input type="text" id="r_nombres" class="form-control" placeholder="Nombre Completo" aria-label="Nombre Completo" aria-describedby="addon-wrapping" required>
                       </div>
                     </div>

                     <!-- Telefono -->
                     <div class="form-outline mb-4">
                       <div class="input-group flex-nowrap">
                         <span class="input-group-text" id="addon-wrapping"><i class="bi bi-telephone-fill"></i></span>
                         <input type="tel" id="r_telefono" maxlength="10" pattern="[0-9]{10}" class="form-control" placeholder="Número Teléfonico" aria-label="Numero Telefonico" aria-describedby="addon-wrapping" required>
                       </div>
                     </div>

                     <!-- Correo -->
                     <div class="form-outline mb-4">
                       <div class="input-group flex-nowrap">
                         <span class="input-group-text" id="addon-wrapping">@</span>
                         <input type="email" id="r_email" class="form-control" placeholder="Correo Electrónico" aria-label="Correo Electrónico" aria-describedby="addon-wrapping" required>
                       </div>
                     </div>

                     <!-- Contraseña -->
                     <div class="form-outline mb-4">
                       <div class="input-group flex-nowrap">
                         <span class="input-group-text" id="addon-wrapping"><i class="bi bi-lock-fill"></i></span>
                         <input type="password" id="r_password" class="form-control" placeholder="Contraseña" aria-label="Contraseña" minlength="8" aria-describedby="addon-wrapping" required>
                         <!-- Botón para mostrar/ocultar contraseña -->
                         <button type="button" class="btn btn-outline-dark" id="toggle-password" onclick="verPassword()">
                           <i class="bi bi-eye-slash" id="eye-icon"></i>
                         </button>
                       </div>
                     </div>

                     <!-- Confirmar Contraseña -->
                     <div class="form-outline mb-4">
                       <div class="input-group flex-nowrap">
                         <span class="input-group-text" id="addon-wrapping"><i class="bi bi-lock"></i></span>
                         <input type="password" id="r_confirm_password" class="form-control" placeholder="Confirmar Contraseña" minlength="8" aria-label="Confirmar Contraseña" aria-describedby="addon-wrapping" required>
                         <!-- Botón para mostrar/ocultar confirmar contraseña -->
                         <button type="button" class="btn btn-outline-dark" id="toggle-confirm-password" onclick="verConfirmPassword()">
                           <i class="bi bi-eye-slash" id="confirm-eye-icon"></i>
                         </button>
                       </div>
                     </div>

                     <!-- Botón de Registro -->
                     <button type="submit" class="btn btn-dark btn-block mb-4" style="width: 100%;" onclick="opcionesFuncionalidad('crearCuenta')">Crear</button>
                   </form>
                 </div>
               </div>
             </div>
           </div>
         </div>


       </section>
     <?php } ?>
   </main>

   <footer id="footer" class="footer position-relative light-background">
     <div class="container">
       <h3 class="sitename">Encontrado Lideres</h3>
       <div class="social-links d-flex justify-content-center">
         <a href=""><i class="bi bi-youtube"></i></a>
         <a href=""><i class="bi bi-spotify"></i></a>
         <a href=""><i class="bi bi-facebook"></i></a>
         <a href=""><i class="bi bi-instagram"></i></a>
       </div>
     </div>
   </footer>

   <!-- Scroll Top -->
   <a
     href="#"
     id="scroll-top"
     class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

   <!-- Preloader -->
   <div id="preloader"></div>

   <!-- Vendor JS Files -->
   <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
   <script src="assets/vendor/php-email-form/validate.js"></script>
   <script src="assets/vendor/aos/aos.js"></script>
   <script src="assets/vendor/typed.js/typed.umd.js"></script>
   <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
   <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
   <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
   <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
   <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
   <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

   <!-- Main JS File -->
   <script src="assets/js/main.js"></script>
   <script type="text/javascript">
     function loadApp() {

       $('#canvas').fadeIn(1000);

       var flipbook = $('.magazine');

       // Check if the CSS was already loaded

       if (flipbook.width() == 0 || flipbook.height() == 0) {
         setTimeout(loadApp, 10);
         return;
       }

       // Create the flipbook

       flipbook.turn({

         // Magazine width

         width: 922,

         // Magazine height

         height: 600,

         // Duration in millisecond

         duration: 1000,

         // Hardware acceleration

         acceleration: true,

         // Enables gradients

         gradients: true,

         // Auto center this flipbook

         autoCenter: true,

         // Elevation from the edge of the flipbook when turning a page

         elevation: 50,

         // The number of pages

         pages: 12,

         // Events

         when: {
           turning: function(event, page, view) {

             var book = $(this),
               currentPage = book.turn('page'),
               pages = book.turn('pages');

             // Update the current URI

             Hash.go('page/' + page).update();

             // Show and hide navigation buttons

             disableControls(page);


             $('.thumbnails .page-' + currentPage).
             parent().
             removeClass('current');

             $('.thumbnails .page-' + page).
             parent().
             addClass('current');



           },

           turned: function(event, page, view) {

             disableControls(page);

             $(this).turn('center');

             if (page == 1) {
               $(this).turn('peel', 'br');
             }

           },

           missing: function(event, pages) {

             // Add pages that aren't in the magazine

             for (var i = 0; i < pages.length; i++)
               addPage(pages[i], $(this));

           }
         }

       });

       // Zoom.js

       $('.magazine-viewport').zoom({
         flipbook: $('.magazine'),

         max: function() {

           return largeMagazineWidth() / $('.magazine').width();

         },

         when: {

           swipeLeft: function() {

             $(this).zoom('flipbook').turn('next');

           },

           swipeRight: function() {

             $(this).zoom('flipbook').turn('previous');

           },

           resize: function(event, scale, page, pageElement) {

             if (scale == 1)
               loadSmallPage(page, pageElement);
             else
               loadLargePage(page, pageElement);

           },

           zoomIn: function() {

             $('.thumbnails').hide();
             $('.made').hide();
             $('.magazine').removeClass('animated').addClass('zoom-in');
             $('.zoom-icon').removeClass('zoom-icon-in').addClass('zoom-icon-out');

             if (!window.escTip && !$.isTouch) {
               escTip = true;

               $('<div />', {
                 'class': 'exit-message'
               }).
               html('<div>Press ESC to exit</div>').
               appendTo($('body')).
               delay(2000).
               animate({
                 opacity: 0
               }, 500, function() {
                 $(this).remove();
               });
             }
           },

           zoomOut: function() {

             $('.exit-message').hide();
             $('.thumbnails').fadeIn();
             $('.made').fadeIn();
             $('.zoom-icon').removeClass('zoom-icon-out').addClass('zoom-icon-in');

             setTimeout(function() {
               $('.magazine').addClass('animated').removeClass('zoom-in');
               resizeViewport();
             }, 0);

           }
         }
       });

       // Zoom event

       if ($.isTouch)
         $('.magazine-viewport').on('zoom.doubleTap', zoomTo);
       else
         $('.magazine-viewport').on('zoom.tap', zoomTo);


       // Using arrow keys to turn the page

       $(document).on('keydown', function(e) {

         var previous = 37,
           next = 39,
           esc = 27;

         switch (e.keyCode) {
           case previous:

             // left arrow
             $('.magazine').turn('previous');
             e.preventDefault();

             break;
           case next:

             //right arrow
             $('.magazine').turn('next');
             e.preventDefault();

             break;
           case esc:

             $('.magazine-viewport').zoom('zoomOut');
             e.preventDefault();

             break;
         }
       });

       // URIs - Format #/page/1 

       Hash.on('^page\/([0-9]*)$', {
         yep: function(path, parts) {
           var page = parts[1];

           if (page !== undefined) {
             if ($('.magazine').turn('is'))
               $('.magazine').turn('page', page);
           }

         },
         nop: function(path) {

           if ($('.magazine').turn('is'))
             $('.magazine').turn('page', 1);
         }
       });


       $(window).on('resize', function() {
         resizeViewport();
       }).on('orientationchange', function() {
         resizeViewport();
       });

       // Events for thumbnails

       $('.thumbnails').on('click', function(event) {

         var page;

         if (event.target && (page = /page-([0-9]+)/.exec($(event.target).attr('class')))) {

           $('.magazine').turn('page', page[1]);
         }
       });

       $('.thumbnails li').
       on($.mouseEvents.over, function() {

         $(this).addClass('thumb-hover');

       }).on($.mouseEvents.out, function() {

         $(this).removeClass('thumb-hover');

       });

       if ($.isTouch) {

         $('.thumbnails').
         addClass('thumbanils-touch').
         on($.mouseEvents.move, function(event) {
           event.preventDefault();
         });

       } else {

         $('.thumbnails ul').on('mouseover', function() {

           $('.thumbnails').addClass('thumbnails-hover');

         }).on('mousedown', function() {

           return false;

         }).on('mouseout', function() {

           $('.thumbnails').removeClass('thumbnails-hover');

         });

       }


       // Regions

       if ($.isTouch) {
         $('.magazine').on('touchstart', regionClick);
       } else {
         $('.magazine').on('click', regionClick);
       }

       // Events for the next button

       $('.next-button').on($.mouseEvents.over, function() {

         $(this).addClass('next-button-hover');

       }).on($.mouseEvents.out, function() {

         $(this).removeClass('next-button-hover');

       }).on($.mouseEvents.down, function() {

         $(this).addClass('next-button-down');

       }).on($.mouseEvents.up, function() {

         $(this).removeClass('next-button-down');

       }).on('click', function() {

         $('.magazine').turn('next');

       });

       // Events for the next button

       $('.previous-button').on($.mouseEvents.over, function() {

         $(this).addClass('previous-button-hover');

       }).on($.mouseEvents.out, function() {

         $(this).removeClass('previous-button-hover');

       }).on($.mouseEvents.down, function() {

         $(this).addClass('previous-button-down');

       }).on($.mouseEvents.up, function() {

         $(this).removeClass('previous-button-down');

       }).on('click', function() {

         $('.magazine').turn('previous');

       });


       resizeViewport();

       $('.magazine').addClass('animated');

     }

     // Zoom icon

     $('.zoom-icon').on('mouseover', function() {
       if ($(this).hasClass('zoom-icon-in'))
         $(this).addClass('zoom-icon-in-hover');

       if ($(this).hasClass('zoom-icon-out'))
         $(this).addClass('zoom-icon-out-hover');
     }).on('mouseout', function() {
       if ($(this).hasClass('zoom-icon-in'))
         $(this).removeClass('zoom-icon-in-hover');

       if ($(this).hasClass('zoom-icon-out'))
         $(this).removeClass('zoom-icon-out-hover');
     }).on('click', function() {
       if ($(this).hasClass('zoom-icon-in')) {
         $('.magazine-viewport').zoom('zoomIn');
         $(this).removeClass('zoom-icon-in').addClass('zoom-icon-out');
         $(this).html('<i class="bi bi-zoom-out"></i>');
       } else if ($(this).hasClass('zoom-icon-out')) {
         $('.magazine-viewport').zoom('zoomOut');
         $(this).removeClass('zoom-icon-out').addClass('zoom-icon-in');
         $(this).html('<i class="bi bi-zoom-in"></i>');
       }
     });


     $('#canvas').hide();

     // Load turn.js

     loadApp()
   </script>

 </body>

 </html>