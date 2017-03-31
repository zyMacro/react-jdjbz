import React,{Component} from 'React'
import ReactDOM from 'react-dom'
import {Row,Col,Tabs,Table} from 'antd'

const columns=[{title:'历史',dataIndex:'history'},{title:'步数',dataIndex:'steps'},{title:'单位排名',dataIndex:'schoolRank'},
{title:'学校排名',dataIndex:'Rank'},{title:'睡眠时间',dataIndex:'sleepTime'}];
class Average extends React.Component{
	constructor(){
		super();
		this.state={
			data:{}
		}	
	}
	request(){$.post('../public/week_data.php',data=>{
	this.setState({
		data
	})},'json');
	}
	componentDidMount(){
		this.request();
	}
	render(){
		var sleepTime=`${this.state.data.sleepHour}小时${this.state.data.sleepMin}分钟`;
		var data=[{key:'1',history:'平均',steps:this.state.data.steps,schoolRank:this.state.data.rankSchoolText,Rank:this.state.data.rankText,sleepTime:sleepTime}];
		return <Table pagination={false} dataSource={data} columns={columns}>
		</Table>

	}

}
export default Average;