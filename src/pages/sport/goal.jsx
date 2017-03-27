import React, {Component} from 'react'
import ReactDOM from 'react-dom'
var Highcharts = require('highcharts');
require('highcharts/modules/exporting')(Highcharts);
require('highcharts/js/highcharts-more')(ReactHighcharts.Highcharts);
import ReactHighcharts from 'react-highcharts'

// var ReactHighcharts = require('react-highcharts');
// var HighchartsMore = require('highcharts-more');
// HighchartsMore(ReactHighcharts.Highcharts);
// var HighchartsExporting = require('highcharts-exporting');
// HighchartsExporting(ReactHighcharts.Highcharts);
// import ReactHighcharts from 'react-highcharts'

class Goal extends React.Component{
	constructor(){
		super();
		this.state={
			'steps':0,
			'goal':0,
		}
	}
	request(){$.post('../public/basic_data.php',data=>{
	this.setState({steps:parseInt(data.steps),goal:parseInt(data.stepsGoal)});
         },'json');
	}
	componentDidMount(){
		this.request();
	}
	render(){
    var config={
        chart: {
            height:200,
            type: 'solidgauge'
        },
        title: '今日目标完成度',
        pane: {
            center: ['50%', '100%'],
            size: '140%',
            startAngle: -90,
            endAngle: 90,
            background: {
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                innerRadius: '60%',
                outerRadius: '100%',
                shape: 'arc'
            }
        },
        tooltip: {
            enabled: false
        },
        yAxis: {
            stops: [
                [0.1, '#55BF3B'], // green
                [0.5, '#DDDF0D'], // yellow
                [0.9, '#DF5353'] // red
            ],
            lineWidth: 0,
            minorTickInterval: null,
            tickPixelInterval: 400,
            tickWidth: 0,
            title: {
                y: -70
            },
            labels: {
                y: 16
            }
        },
        plotOptions: {
            solidgauge: {
                dataLabels: {
                    y: 5,
                    borderWidth: 0,
                    useHTML: true
                }
            }
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'goal',
            data: [this.state.steps],
            dataLabels: {
                format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                '<span style="font-size:12px;color:silver">步</span></div>'
            },
            tooltip: {
                valueSuffix: '步'
            }
        }]
    }
    return <ReactHighcharts config={config}></ReactHighcharts>
    }

}
export default Goal




