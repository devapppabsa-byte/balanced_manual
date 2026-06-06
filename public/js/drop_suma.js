const suma_container = document.getElementById('suma_container');
const letrero_suma = document.getElementById('letrero_suma');


let nodo_arrastrado_suma = null;



function dragStartSuma(e){

    nodo_arrastrado_suma = e.target;
    e.dataTransfer.setData('text', e.target.id);

    suma_container.classList.add("border-dashed");
    letrero_suma.innerHTML = "<b> Suelta el campo aqui debajo </b>"


}

function allowDropSuma(e){

    e.preventDefault();

}


function dropSuma(e){

    suma_container.classList.remove("border-dashed");
    suma_container.classList.add("border");
    suma_container.classList.add("p-2");
    letrero_suma.innerHTML = "Arrastra los campos a sumar";

    e.preventDefault();


    if(nodo_arrastrado_suma){
        
        let destino = e.target;
        const id = e.dataTransfer.getData('text');

        if(!destino.classList.contains('no-drop')){

            //verificando si existe el nodo

            destino.appendChild(nodo_arrastrado_suma)
        
        
        }

    }

    
}
