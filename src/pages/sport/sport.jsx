import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import MenuList from '../home/menu.jsx'
import Goal from './goal.jsx'
import DataTable from '../home/dataTable.jsx'


var Highcharts=require('highcharts');

class Sport extends React.Component{
	render(){
		return <div>
        <MenuList></MenuList>
		<Goal></Goal>
		<DataTable></DataTable>
		
		</div>
	}

}
export default Sport
	// 
// import Average from './average.jsx'
// import WeekStepsSleep from './weekStepsSleep.jsx'
// <Average></Average>
// 		<WeekStepsSleep></WeekStepsSleep>