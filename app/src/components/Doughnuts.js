import Doughnut from "vue-chartjs";
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
    extends: Doughnut,
    props: ["data", "options"],
    mounted() {
        this.addPlugin({
            id: 'my-plugin',
            beforeDraw: plugin
        })
        // this.chartData is created in the mixin.
        // If you want to pass options please create a local options object
        this.renderChart(this.data, {
            borderWidth: "10px",
            hoverBackgroundColor: "red",
            hoverBorderWidth: "10px"
        });
    }
};
