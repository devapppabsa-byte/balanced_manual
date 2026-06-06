//variables a ocupar
let nodo_arrastrado_division = null;
const divisor_container = document.getElementById('divisor_container'); 
const dividendo_container = document.getElementById('dividendo_container');



function dragStartDivision(e){

    nodo_arrastrado_division = e.target;
    e.dataTransfer.setData('text', e.target.id);

    //aqui va la logica de la interfaz de usuario
    divisor_container.classList.add('border-dashed');
    divisor_container.classList.remove('border');
    
    dividendo_container.classList.add('border-dashed');
    dividendo_container.classList.remove('border');
    //aqui termina la logica de la interfaz


}

function allowDropDivision(e){

    e.preventDefault();

}

function dropDivision(e){

    //al soltar el nodo (no importa donde) se va a 
    dividendo_container.classList.remove('border-dashed');
    dividendo_container.classList.add('border');
    
    divisor_container.classList.remove('border-dashed');
    divisor_container.classList.add('border');
    //al soltar el nodo (no importa donde) se resetean los estilos del container
    

    e.preventDefault();
    
    
    if(nodo_arrastrado_division){

        let destino = e.target;
        //const id =  e.dataTransfer.getData('text');
        
        if(!destino.classList.contains('no-drop')){

            destino.innerHTML = "";

            const copia = nodo_arrastrado_division.cloneNode(true);
            destino.appendChild(copia)

        }

    }





}