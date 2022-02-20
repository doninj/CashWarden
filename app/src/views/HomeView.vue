<script>
import MenuLayout from "@/components/layouts/MenuLayout.vue"
import Vue3ChartJs from '@j-t-mcc/vue3-chartjs'
import {ref} from 'vue';

var plugin = function (chart) {
  var width = chart.chart.width;
  var height = chart.chart.height;
  var ctx = chart.chart.ctx;

  ctx.restore();
  var fontSize = (height / 114).toFixed(2);
  ctx.font = fontSize + "em sans-serif";
  ctx.textBaseline = "middle";
  var text = "Solde:";
  var textX = Math.round((width - ctx.measureText(text).width) / 2);
  var textY = height / 2;

  ctx.fillText(text, textX, textY);
  ctx.save();
};


export default {
  name: 'App',
  components: {
    MenuLayout
  },
  setup() {
    const chartData = ref({
      labels: ['A', 'B', 'C'],
      datasets: [
        {
          data: [300, 50, 100],
          backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"],
          hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"]
        }
      ]
    });
    const lightOptions = ref({
      plugins: {
        legend: {
          labels: {
            color: '#495057'
          }
        }
      }
    });

    return { chartData, lightOptions }
  }
}
</script>

<template>
  <div>
  <MenuLayout>
    <div class="flex flex-column">
      <div class="mb-5">
        <h2 class="font-bold"> Tableau de bord</h2>
      </div>
      <div class=" mt-3 grid justify-content-between">
        <div class="card-info col-3">
          <div class="flex flex-column align-items-center text-center">
            <span class="text-name">Budget prévionnel du <br>mois</span>
            <span class="text-price">1000€</span>
          </div>
        </div>
        <div class="card-info_principale col-3">
          <div class="flex flex-column align-items-center text-center">
            <span class="text-name_principale">Compte courant <br> actuel</span>
            <span class="text-price_principale">1000€</span>
          </div>
        </div>
        <div class="card-info col-3">
          <div class="flex flex-column align-items-center text-center">
            <span class="text-name">Budget prévionnel du <br> mois</span>
            <span class="text-price">1000€</span>
          </div>
        </div>
      </div>
      <div class=" mt-3 grid justify-content-between">
        <div class="card-content-info_bancaire col-12">
          <div class="flex flex-column">
            <div class="mb-5">
              <h2 class="font-bold"> Statistique bancaire</h2>
            </div>
            <div class="flex">
              <Chart type="doughnut" :data="chartData" :options="lightOptions"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </MenuLayout>
  </div>
</template>

<style>
.card-content-info_bancaire {
  background-color: white;
  border: 0.1px solid #A4A6B3;
  border-radius: 6px;
}

.card-info {
  background-color: white;
  border: 2px solid #A4A6B3;
  border-radius: 6px;
}

.card-info_principale {
  background-color: white;
  border: 2px solid #3751FF;
  border-radius: 6px;
  box-shadow: 0 0 0 2px #DDE2FF;
}

.text-price {
  font-weight: bold;
  font-size: 30px;
}

.text-price_principale {
  font-weight: bold;
  font-size: 30px;
  color: #3751FF;
}

.text-name_principale {
  font-size: 20px;
  color: #3751FF;
}

.text-name {
  font-size: 20px;
  color: #9FA2B4;
}
</style>
