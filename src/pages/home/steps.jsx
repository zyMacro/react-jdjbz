import React,{Component} from 'React'
import ReactDOM from 'react-dom'
import {Row,Col} from 'antd'

class Steps extends React.Component{
	constructor(){
		super();
		this.state={
			steps:0,
		}
		
	}
	request(){$.post('../public/basic_data.php',data=>{
		this.setState({steps:data})
	         })}
	// method2 
	// request(){$.post('../public/basic_data.php',function(data,textStatus){
	// 	this.setState({steps:data})
	//          }.bind(this))}
	componentDidMount(){
		this.request();
		// var data=this.request();
		// this.setState({steps:data})
	}
	render(){
		return <div>
		<Row>
		<Col span={6}>运动</Col>
		<Col span={9}>步数</Col>
		<Col span={9}>{this.state.steps}</Col>
		</Row>
		</div>
	}
}
export default Steps