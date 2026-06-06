let nodo_arrastrado_resta = null;
const minuendo_container = document.getElementById('minuendo_container');
const sustraendo_container = document.getElementById('sustraendo_container');


function dragStartResta(e){

    nodo_arrastrado_resta = e.target;
    e.dataTransfer.setData('text', e.target.id);


    //logica 
    minuendo_container.classList.add('border-dashed');
    minuendo_container.classList.remove('border');


    sustraendo_container.classList.add('border-dashed');
    sustraendo_container.classList.remove('border');
    

}


function allowDropResta(e){

    e.preventDefault();

}

function dropResta(e){

    minuendo_container.classList.remove('border-dashed');
    minuendo_container.classList.add('border');

    sustraendo_container.classList.remove('border-dashed');
    sustraendo_container.classList.add('border');


    e.preventDefault();

    if(nodo_arrastrado_resta){
        
        let destino = e.target;

        if(!destino.classList.contains('no-drop')){

            destino.innerHTML = "";
            const copia = nodo_arrastrado_resta.cloneNode(true);
            destino.appendChild(copia)
            
        }


    }

}