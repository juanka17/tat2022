function buildBarGraphicOptions( title , categories , series , money )
{
    return {
        title: {
            text: title
        },
        chart: {
            type: 'bar',
            height: 500,
            stacked: true
        },
        plotOptions: {
            bar: {
                horizontal: true,
            },
        },
        dataLabels: {
            enabled: true,
            formatter: function(value) {
                if( money )
                {
                    value = Math.round( value / 1000000, 0);
                    return "$" + value.toLocaleString( undefined,  { minimumFractionDigits: 0 } ) + "M";
                }
                return value.toLocaleString( undefined,  { minimumFractionDigits: 0 } );
            }
        },
        series: series,
        xaxis: {
            categories: categories,
            labels: {
                formatter: function ( value ) {
                    if( money )
                    {
                        return "$" + value.toLocaleString( undefined,  { minimumFractionDigits: 0 } );
                    }
                    return value.toLocaleString( undefined,  { minimumFractionDigits: 0 } );
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            offsetX: 40
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            shared: true,
            y: {
                formatter: function (value) {
                    if( money )
                    {
                        return "$" + value.toLocaleString( undefined,  { minimumFractionDigits: 0 } );
                    }
                    return value.toLocaleString( undefined,  { minimumFractionDigits: 0 } );
                }
            }
        }
    };
}

function drawGraphic( selector , options )
{
    $( selector ).html("");
    var chart = new ApexCharts(document.querySelector( selector ), options);
    chart.render();
}

function showGraficaTerritorios( title )
{
    data_graficas.territorios[title].title
    let options = buildBarGraphicOptions( 
        data_graficas.territorios[title].title , 
        data_graficas.territorios.categories[title] , 
        data_graficas.territorios[title].series ,
        title == "ventas"
    );
    drawGraphic( "#GraficaTerritorios" , options );
}

dg = dg;

let datos_territorios = {
    ventas: { title: "Ventas" , series: [] },
    impactos: { title: "Impactos" , series: [] },
    dropsize: { title: "Dropsize" , series: [] },
    categories: { ventas: [], impactos: [], dropsize: [] }
};

let totales = { ventas: [] , impactos: [] , dropsize: [] };
let categories = [];
let preview_data = [];
preview_data["Norte"] = { ventas: [] , impactos: [] , dropsize: [] };
preview_data["Centro"] = { ventas: [] , impactos: [] , dropsize: [] };
preview_data["Sur"] = { ventas: [] , impactos: [] , dropsize: [] };
preview_data["Santander"] = { ventas: [] , impactos: [] , dropsize: [] };

let total_ventas = 0;
let total_impactos = 0;
let temp_periodo = "";
for (i in dg.data)
{
    if( categories.indexOf( dg.data[i].periodo ) == -1 )
    {
        categories.push( dg.data[i].periodo );
    }
    preview_data[dg.data[i].territorio].ventas.push( dg.data[i].ventas );
    preview_data[dg.data[i].territorio].impactos.push( dg.data[i].impactos );
    preview_data[dg.data[i].territorio].dropsize.push( (dg.data[i].ventas / dg.data[i].impactos).toFixed(0) );
    
    if( temp_periodo != "" && temp_periodo != dg.data[i].periodo )
    {
        totales.ventas.push( total_ventas );
        totales.impactos.push( total_impactos );
        totales.dropsize.push( (total_ventas / total_impactos).toFixed(0) );
        total_ventas = 0;
        total_impactos = 0;
    }
    total_ventas += dg.data[i].ventas;
    total_impactos += dg.data[i].impactos;
    temp_periodo = dg.data[i].periodo;
}
totales.ventas.push( total_ventas );
totales.impactos.push( total_impactos );
totales.dropsize.push( (total_ventas / total_impactos).toFixed(0) );

for ( territorio in preview_data )
{
    datos_territorios.ventas.series.push( { name: territorio, data: preview_data[territorio].ventas } );
    datos_territorios.impactos.series.push( { name: territorio, data: preview_data[territorio].impactos } );
    datos_territorios.dropsize.series.push( { name: territorio, data: preview_data[territorio].dropsize } );
}

console.log(categories);
for ( i_periodo in categories )
{
    let total = "$" + totales.ventas[i_periodo].toLocaleString( undefined,  { minimumFractionDigits: 0 } );
    datos_territorios.categories.ventas.push( [ categories[i_periodo] , total ] );
    
    total = totales.impactos[i_periodo].toLocaleString( undefined,  { minimumFractionDigits: 0 } );
    datos_territorios.categories.impactos.push( [ categories[i_periodo] , total ] );

    total = totales.dropsize[i_periodo].toLocaleString( undefined,  { minimumFractionDigits: 0 } );
    datos_territorios.categories.dropsize.push( [ categories[i_periodo] , total ] );
}

data_graficas["territorios"] = datos_territorios;
showGraficaTerritorios("ventas");

$(".btn-cambiar-fuente-grafica-territorios").on("click", function(){
    let fuente = $(this).data("fuente");
    showGraficaTerritorios(fuente);
});