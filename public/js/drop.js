// variables de los campos de los campos para el promedio
const promedio_container = document.getElementById('promedio_container'); 
const letrero_promedio = document.getElementById('letrero_promedio');
//variables del campo promedio

//variables del campo ultiplicacion
const multiplicacion_container = document.getElementById('multiplicacion_container');
const letrero_multiplicacion = document.getElementById('letrero_multiplicacion');
//variables del campo multiplicacion




//variables para el campo de division
const division_container = document.getElementById("division");
//variables para el campo de division




    let nodo_arrastrado = null;


    function dragStart(e){

    nodo_arrastrado = e.target;
    e.dataTransfer.setData('text', e.target.id);

    console.log("Aqui va la logica para cuando el nodo se empieza a arrastrar.")
  
    //Aqui tenemos el manejo de la UX XD
    promedio_container.classList.add("border-dashed");
    letrero_promedio.innerHTML= "<b> Suelta el campo aqui debajo </b>"
    //Manejo del DOM del container

    


    //el UX de los campos de multiplicacion
    multiplicacion_container.classList.add("border-dashed");
    letrero_multiplicacion.innerHTML = "<b> Suelta el campo aqui debajo </b>";
    //el UX de los campos de multiplicacion



    //UX de los campos  de suma
        suma_container.classList.add("border-dashed");
        letrero_suma.innerHTML = "<b> Suelta el campo aqui debajo </b>";
    //UX de los campos de suma


    }


    function allowDrop(e){
        e.preventDefault();

    }




    function drop(e){

        console.log('Aqui va la logica para cuando se suelta el nodo')

        //UX del campo de promedio
        promedio_container.classList.remove('border-dashed');
        letrero_promedio.innerText = "Arrastra los campos a promediar";
        //UX del campo promedio termina aqui



        //UX del campo de multiplicaciones la ctm
        multiplicacion_container.classList.remove('border-dashed');
        letrero_multiplicacion.innerText = "Arrastra los campos a multiplicar";
        //UX del campo de multiplicaciones la ctm, termina aqui



        //UX del campo de suma
        suma_container.classList.remove('border-dashed');
        letrero_suma.innerText = "Arrastra los campos a sumar";
        //UX del campo de suma


        e.preventDefault();



        if(nodo_arrastrado){

            let destino = e.target;
            const id = e.dataTransfer.getData('text');


            if(!destino.classList.contains('no-drop')){
                destino.appendChild(nodo_arrastrado);
            }

        }
    }
