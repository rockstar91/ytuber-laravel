<template>
  <b-card v-if="!!!obmenStatWeek" body-bg-variant="white" header-text-variant="dark" header-bg-variant="light-grey" bg-variant="light">
    <b-overlay :show="showStatsOverlay" rounded="sm" class="text-center"/>
    <template #header>
      <h3><i class="icon-stats-bars3 icon-2x mr-2"></i>Активность</h3>
      <small>Ваша активность за последние 7 дней</small>
    </template>
    <b-card-body style="padding-top: 0px;">
    <div class="chart">
      <client-only placeholder="Loading...">
      <Bar v-if="!showStatsOverlay" :data="chartData" :options="chartOptions" :height="280" />
      </client-only>
    </div>
    </b-card-body>
  </b-card>
</template>

<script>
export default {
  data() {
    return {
      showStatsOverlay:true,
      obmenStatWeek:[],
      chartData: {
        labels: [],
        datasets: [],
      },
      chartOptions: {
        responsive: true,
        legend: {
          display: false,
        },
        title: {
          display: true,
          text: "",
          fontSize: 24,
          fontColor: "#000",
        },
        tooltips: {
          backgroundColor: "#ffa726",
        },
        scales: {
          xAxes: [
            {
              gridLines: {
                display: true,
              },
            },
          ],
          yAxes: [
            {
              ticks: {
                beginAtZero: true,
                max: 200,
                min: 0,
                stepSize: 10,
              },
              gridLines: {
                display: true,
              },
            },
          ],
        },
      },
    };
  },
  mounted(){
    this.getStatObmenWeek();
  },
  methods:{
    getStatObmenWeek(){
      this.$axios.$get('/api/complete-stat').then(res=>{
        this.obmenStatWeek = res;
        //this.chartData.labels = [];
        this.chartData.labels = this.obmenStatWeek[1];
        //this.chartData.datasets = [];
        this.chartData.datasets.push(
            {
              label: 'Выполнено',
              backgroundColor: '#f87979',
              data:this.obmenStatWeek[0]
            }
        );
        this.showStatsOverlay = false;
      })
    }
  }
};
</script>