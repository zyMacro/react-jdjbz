import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import RankMenu from './rankMenu.jsx'
import RankAll from './rankAll.jsx'

class Rank extends React.Component{
	render(){
		return <div> 
		<RankMenu></RankMenu>
		<RankAll></RankAll>

		</div>
		
	}
}
export default Rank
	// <RankAverage></RankAverage>
	// 	<RankSchoolAll></RankSchoolAll>
	// 	<RankSchoolAverage></RankSchoolAverage>