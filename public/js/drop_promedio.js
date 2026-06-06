
const promedio_container = document.getElementById("promedio_container");
const letrero_promedio = document.getElementById("letrero_promedio");
let nodo_arrastrado_promedio = null;



function dragStartPromedio(e){

    nodo_arrastrado_promedio = e.target;
    e.dataTransfer.setData('text', e.target.id);
     promedio_container.classList.add("border-dashed");
     letrero_promedio.innerHTML = "<b> Suelta el campo aqui debajo </b>";

}

function allowDropPromedio(e){

    e.preventDefault();

}


function dropPromedio(e){


    promedio_container.classList.remove('border-dashed');
    letrero_promedio.innerText = "Arrastra los campos a promediar";

    e.preventDefault();
    

    if(nodo_arrastrado_promedio){
        
        let destino = e.target;
        const id = e.dataTransfer.getData('text');
        
        if(!destino.classList.contains('no-drop')){

            //verificando si existe el nodo
            const existe = Array.from(destino.children).some(child => child.dataset.originalId === id);

            if(existe){
                alert('El campo ya fue agregado anteriormente.!')
                return;
            }
                        

            const copia = nodo_arrastrado_promedio.cloneNode(true);
            //Guardamos el id original en un dataset.
            copia.dataset.originalId = id;
            destino.appendChild(copia);

        }
    
    
    }


}