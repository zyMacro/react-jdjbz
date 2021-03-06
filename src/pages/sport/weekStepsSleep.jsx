import React, { Component } from 'React'
import ReactDOM from 'react-dom'

var ReactHighcharts = require('react-highcharts');
var HighchartsMore = require('highcharts-more');
HighchartsMore(ReactHighcharts.Highcharts);
var HighchartsExporting = require('highcharts-exporting');
HighchartsExporting(ReactHighcharts.Highcharts);
require('highcharts/js/highcharts-more')(ReactHighcharts.Highcharts);

class WeekStepsSleep extends React.Component {
  constructor() {
    super();
    this.state = {
      data: {stepsArray:[]},
    }

  }
  request() {
    $.post('../public/week_data.php', data => {
      this.setState({
        data
      });
    }, 'json');
    
  }
  componentDidMount() {
    this.request();
  }
  render() {
    // 	for(let $j=0;$j<this.state.weekArray.length;$j++){              //利用匿名函数模仿块级作用域
    // 	xAxisArray[$j]=this.state.weekArray[$j]+'<br>'+'(星期'+this.state.dataArray[$j]+')';
    // }
    // this.setState({
    const data = this.state.data;

    let sleepHourTotal = [];
    let topSchoolPersent = [];
    let topTotalPersent = [];
    let xAxisArray = [];
    // console.log(data);

    for (let $j = 0; $j < data.stepsArray.length; $j++) {
      topSchoolPersent[$j] = Number(((data.rankSchoolArray[$j] / data.numPlayersSchoolArray[$j] * 100).toFixed(2)));
      topTotalPersent[$j] = Number(((data.rankUnivArray[$j] / data.numPlayersArray[$j] * 100).toFixed(2)));
    }
    for (let $i = 0; $i < data.stepsArray.length; $i++) {
      sleepHourTotal[$i] = Number(((data.sleepHourArray[$i] * 60 + data.sleepMinArray[$i]) / 60).toFixed(2));
    }

    let stepsArray = data.stepsArray.map(function(item, index, array) {
      return parseInt(item)
    });


    for (let $j = 0; $j < data.stepsArray.length; $j++) { //利用匿名函数模仿块级作用域
      xAxisArray[$j] = data.weekArray[$j] + '<br>' + '(星期' + data.dateArray[$j] + ')';
    }

    // const stepsArray=parseInt(this.state.data.stepsArray);
    // const sleepHourTotal=Number(((this.state.data.sleepHourArray*60+this.state.data.sleepMinArray)/60).toFixed(2));
    // const topSchoolPersent=Number(((this.state.data.rankSchoolArray/this.state.data.numPlayersSchoolArray*100).toFixed(2)));
    // const topTotalPersent=Number(((this.state.data.rankUnivArray/this.state.data.numPlayersArray*100).toFixed(2)));
    // const xAxisArray= `${this.state.data.weekArray}<br>(星期${this.state.data.dataArray})`;
    // })
    var config = {
      credits: {
        enabled: false //不显示LOGO 
      },
      title: {
        text: '最近一周步数情况和睡眠情况',
        labels: {
          style: {
            fontSize: '30px'
          }
        }
      },
      xAxis: {
        categories: xAxisArray,
        labels: {
          style: {
            // fontWeight:'bold',
            fontSize: '18px',
            color: 'black',

          }
        }
      },
      yAxis: [{
        title: {
          text: '步数',
          style: {
            color: 'black',
            fontSize: '18px'
          }
        },
        labels: {
          format: '{value} 步',
          style: {
            color: 'black',
            // fontWeight:'bold',
            fontSize: '18px'
          }
        }

      },
        {
          title: {
            text: '睡眠情况',
            style: {
              color: 'black',
              fontSize: '18px'
            }

          },
          labels: {
            format: '{value} h',
            style: {
              color: 'black',
              fontSize: '18px'
            }
          },
          opposite: true

        }],

      legend: {
        layout: 'vertical',
        align: 'left',
        x: 120,
        verticalAlign: 'top',
        y: 20,
        floating: true,
        backgroundColor: (ReactHighcharts.theme && ReactHighcharts.theme.legendBackgroundColor) || '#FFFFFF',
        labels: {
          style: {
            fontSize: '18px'
          }
        }
      },
      plotOptions: {
        spline: {
          dataLabels: {
            enabled: true
          },

        },


      },
      tooltip: {
        // shared:true,
        // crosshairs:[true,true],

        style: {
          fontSize: '18px'
        },

        formatter: function() {
          // alert($i);
          var $index = this.point.index;
          if (this.series.name == '步数') {


            return xAxisArray[$index] + '<br>' + '步数: ' + stepsArray[$index] + '步' + '<br>' + '单位排名: top' + topSchoolPersent[$index] + '%' + '<br>' + '学校排名: top' + topTotalPersent[$index] + '%';
          } else {
            return xAxisArray[$index] + '<br>' + '睡眠情况: ' + this.y + 'h';
          }



        }
      },
      series: [

        {

          name: '步数',
          type: 'spline',
          yAxis: 0,

          dataLabels: {
            enabled: true,
            style: {
              fontSize: '18px'
            }
          // formatter:function(){
          // 	 var $i=this.point.index;
          // 	 return $steps_array[$i]+'步'+'<br>'+'单位排名: top'+$top_school_persent[$i]+'%';
          },

          // data: [Number($steps_array[5]),$steps_array[5],$steps_array[2],$steps_array[3],$steps_array[4],$steps_array[5],$steps_array[6]]
          data: stepsArray
        },
        {

          name: '睡眠情况',
          type: 'spline',
          yAxis: 1,
          data: sleepHourTotal,
          dataLabels: {
            style: {
              fontSize: '18px'
            }

          }
        // tooltip:{
        //     enabled:false,
        // 	valueSuffix:'h'
        //        }
        }
      ]

    }
    return <ReactHighcharts config={config}></ReactHighcharts>
  }

}
export default WeekStepsSleep;

// 	for(let $i=0;$i<this.state.stepsArray.length;$i++){
//     	  this.state.stepsArray[$i]=parseInt(this.state.stepsArray[$i]);
//     }
//     // var $sleep_hour_total=new Array();
//  for(let $i=0;$i<7;$i++){
//         this.state.sleepHourTotal[$i]=Number(((this.state.sleepHourArray[$i]*60+this.state.sleepMinArray[$i])/60).toFixed(2));
//  }
//  for(let $j=0;$j<this.state.stepsArray.length;$j++){
// 	this.state.topSchoolPersent[$j]=Number(((this.state.rankSchoolArray[$j]/this.state.numPlayersSchoolArray[$j]*100).toFixed(2)));
// 	this.state.topTotalPersent[$j]=Number(((this.state.rankUnivArray[$j]/this.state.numPlayersArray[$j]*100).toFixed(2)));
// }
// for(let $j=0;$j<this.state.weekArray.length;$j++){              //利用匿名函数模仿块级作用域
// 	this.state.xAxisArray[$j]=this.state.weekArray[$j]+'<br>'+'(星期'+this.state.dataArray[$j]+')';
// }