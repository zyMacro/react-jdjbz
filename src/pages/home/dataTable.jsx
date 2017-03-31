import React,{Component} from 'React'
import ReactDOM from 'react-dom'
import {Row,Col,Tabs,Table} from 'antd'

const TabPane=Tabs.TabPane;
const columns1=[{title:'步数',dataIndex:'steps'},{title:'单位排名',dataIndex:'rankSchool'},{title:'学校排名',dataIndex:'rank'}];
const columns2=[{title:'入睡时间',dataIndex:'sleepStartTime'},{title:'醒来时间',dataIndex:'sleepEndTime'},{title:'深睡时长',dataIndex:'deepSleepTime'},{title:'浅睡时长',dataIndex:'shallowSleepTime'}];

function callback(key){
	console.log(key);
}
class DataTable extends React.Component{
	constructor(){
		super();
		this.state={
			steps:0,
			rank:0,
			rankSchool:0,
			sleepStartTime:'',
			sleepEndTime:'',
			deepSleepTime:'',
			shallowSleepTime:'',
		}
	}
	request(){$.post('../public/basic_data.php',data=>{
		this.setState({
			steps:data.steps,
			rank:data.rank,
			numPlayers:data.numPlayers,
			rankSchool:data.rankSchool,
			numPlayersSchool:data.numPlayersSchool,
			sleepStartTime:data.sleepStartTime,
			sleepEndTime:data.sleepEndTime,
			deepSleepTime:data.deepSleepTime,
			shallowSleepTime:data.shallowSleepTime});
	         },'json');
	}
	// method2 
	// request(){$.post('../public/basic_data.php',function(data,textStatus){
	// 	this.setState({steps:data})
	//          }.bind(this))}
	componentDidMount(){
		this.request();
	}
	render(){
		var data1=[{key:'1',steps:this.state.steps,rank:this.state.rank+'/'+this.state.numPlayers,rankSchool:this.state.rankSchool+'/'+this.state.numPlayersSchool}];
		var data2=[{key:'1',sleepStartTime:this.state.sleepStartTime,sleepEndTime:this.state.sleepEndTime,deepSleepTime:this.state.deepSleepTime,shallowSleepTime:this.state.shallowSleepTime}];
		return <Tabs onChange={callback} type='card'>
		<TabPane tab='运动' key='1'>
        <Table pagination={false} columns={columns1} dataSource={data1} size='middle' />
		</TabPane>
		<TabPane tab='睡眠' key='2'>
		<Table pagination={false} columns={columns2} dataSource={data2} size='middle' />
		</TabPane>
		<TabPane pagination={false} tab='饮食' key='3'>饮食</TabPane>
		</Tabs>
	}
}
export default DataTable