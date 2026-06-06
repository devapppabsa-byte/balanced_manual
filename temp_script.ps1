$file = "c:\Users\desar\Desktop\balanced_dev\resources\views\admin\agregar_indicadores.blade.php"
$content = Get-Content $file -Raw
$pattern = '@section\(''scripts''\)\s*\n\s*\n\s*\n\s*\n\s*\n\s*\n@endsection'
$replacement = '@section(''scripts'')

<script>
document.addEventListener(''DOMContentLoaded'', function() {
    const buscador = document.getElementById(''buscador_indicadores'');
    const contenedor = document.getElementById(''contenedor_indicadores'');
    const items = document.querySelectorAll(''.item-indicador'');
    
    if (buscador) {
        buscador.addEventListener(''input'', function() {
            const termino = this.value.toLowerCase();
            
            items.forEach(function(item) {
                const nombre = item.dataset.nombre;
                const departamento = item.dataset.departamento;
                
                if (nombre.includes(termino) || departamento.includes(termino)) {
                    item.style.display = ''block'';
                } else {
                    item.style.display = ''none'';
                }
            });
        });
    }
});
</script>

@endsection'

$newContent = $content -replace $pattern, $replacement
Set-Content $file $newContent
