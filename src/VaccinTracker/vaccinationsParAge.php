

<h2 style="margin-top: 40px;" id="vaccinations-par-age">Vaccinations par âge</h2>
Mise à jour : <span id="dateMajParAge">--/--</span>

<div>
    <select name="type_age" id="type_age" onchange="typeDonneesBarChartAge()">
        
        <option value="pop">Proportion de la population</option>
        <option value="abs">Personnes vaccinées</option>
    </select>
    </div>

<div class="chart-container" style="position: relative; height:50vh; width:100%">
    <canvas id="barChartAge" style="margin-top:20px; max-height: 700px; max-width: 900px;"></canvas>
</div>
CovidTracker.fr - Données : Ministère de la Santé
<br>
<script>
var data_age;
var barChartAge;

fetch('https://raw.githubusercontent.com/rozierguillaume/vaccintracker/main/data/output/vacsi-tot-a-fra_lastday.json', {cache: 'no-cache'})
       .then(response => {
           if (!response.ok) {
               throw new Error("HTTP error " + response.status);
           }
           return response.json();
       })
       .then(json => {
          this.data_age = json;
          buildLineChartAgePop();
        })
       .catch(function () {
           this.dataError = true;
           console.log("error1")
       }
      )

function typeDonneesBarChartAge(){
    type_donnees = document.getElementById("type_age").value
    this.barChartAge.destroy()
    if(type_donnees=="pop"){
        buildLineChartAgePop();
    } else {
        buildLineChartAge();
    }
}

function buildLineChartAge(type){
    
    
    var ctx = document.getElementById('barChartAge').getContext('2d');

    this.barChartAge = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: data_age.age,
            datasets: [
                {
                label: 'Nombre de vaccinés (complètement) ',
                data: data_age["n_tot_complet"],
                borderWidth: 3,
                backgroundColor: "#1796e6",
                borderWidth: 0,
                cubicInterpolationMode: 'monotone',
            },
            {
                label: 'Nombre de vaccinés (partiellement) ',
                data: data_age["n_tot_dose1"],
                borderWidth: 3,
                backgroundColor: "#a1cbe6",
                borderWidth: 0,
                cubicInterpolationMode: 'monotone',
            },
           
            ]
        },
        options: {
            tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let value = data['datasets'][tooltipItem.datasetIndex]['data'][tooltipItem['index']].toString().split(/(?=(?:...)*$)/).join(' ');
                            return data['datasets'][tooltipItem.datasetIndex]['label'] + ': ' + value;
                        }
                    }
                },
            maintainAspectRatio: false,
            scales: {
						xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                    callback: function (value) {
                                        return value/1000 +" k";
                                    }
                                },
							stacked: false,
                            
						}],
						yAxes: [{
                            gridLines: {
                                display: false
                            },
							stacked: true
						}]
					},
            plugins: {
                deferred: {
                    xOffset: 150,   // defer until 150px of the canvas width are inside the viewport
                    yOffset: '50%', // defer until 50% of the canvas height are inside the viewport
                    delay: 200      // delay of 500 ms after the canvas is considered inside the viewport
                }
                },
            annotation: {
            events: ["click"],
            annotations: [
            ]
        }
        }
    });
    }

    function buildLineChartAgePop(){
        
        let date = data_age.date
        document.getElementById("dateMajParAge").innerHTML = date.slice(8) + "/" + date.slice(5, 7);

        var ctx = document.getElementById('barChartAge').getContext('2d');
        console.log(data_age["couv_tot_complet"])
        this.barChartAge = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: data_age.age,
                datasets: [
                    {
                    label: 'Vaccinés (complètement) ',
                    data: data_age["couv_tot_complet"],
                    borderWidth: 3,
                    backgroundColor: "#1796e6",
                    borderWidth: 0,
                    cubicInterpolationMode: 'monotone',
                },
                {
                    label: 'Vaccinés (partiellement) ',
                    data: data_age["couv_tot_dose1"],
                    borderWidth: 3,
                    backgroundColor: "#a1cbe6",
                    borderWidth: 0,
                    cubicInterpolationMode: 'monotone',
                },
                {
                    label: 'Non vaccinés ',
                    data: data_age["couv_tot_dose1"].map((value, idx)=> ([100])),
                    borderWidth: 3,
                    backgroundColor: "#ededed",
                    borderWidth: 0,
                    cubicInterpolationMode: 'monotone',
                },
            
                ]
            },
            options: {
                tooltips: {
                    filter: function (tooltipItem) {
                        return tooltipItem.datasetIndex != 2;
                    },
                    callbacks: {
                        label: function(tooltipItem, data) {
                        return data['datasets'][tooltipItem.datasetIndex]['label'] + ': ' + data['datasets'][tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' %';
                        }
                    }
                },
                maintainAspectRatio: false,
                scales: {
                            xAxes: [{
                                gridLines: {
                                    display: true
                                },
                                ticks: {
                                    min: 0,
                                    max:100,
                                    callback: function (value) {
                                        return value + ' %';
                                    }
                                },
                                stacked: false,
                                
                            }],
                            yAxes: [{
                                gridLines: {
                                    display: false
                                },
                                stacked: true
                            }]
                        },
                plugins: {
                    deferred: {
                        xOffset: 150,   // defer until 150px of the canvas width are inside the viewport
                        yOffset: '50%', // defer until 50% of the canvas height are inside the viewport
                        delay: 200      // delay of 500 ms after the canvas is considered inside the viewport
                    }
                    },
                annotation: {
                events: ["click"],
                annotations: [
                ]
            }
            }
        });
        
        }


</script>
