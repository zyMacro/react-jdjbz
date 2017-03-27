import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import MenuList from '../home/menu.jsx'
import Goal from './goal.jsx'
import DataTable from '../home/dataTable.jsx'
import WeekStepsSleep from './weekStepsSleep.jsx'


class Sport extends React.Component{
	render(){
		return <div>
        <MenuList></MenuList>
        <DataTable></DataTable>
        <Goal></Goal>
        <WeekStepsSleep></WeekStepsSleep>

		</div>
	}

}
export default Sport
	// 
	// 		import WeekStepsSleep from './weekStepsSleep.jsx'
	//  <WeekStepsSleep></WeekStepsSleep>
// import Average from './average.jsx'
// 
// <Average></Average>
// 		