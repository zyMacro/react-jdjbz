import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import {Table} from 'antd'

const columns1=[{title:'名次',dataIndex:'ranking'},{title:'姓名',dataIndex:'name'},{title:'步数',dataIndex:'steps'}];

class RankAverage extends React.Component{
	constructor(){
		super();
		this.state={
			data:{}
		}
	}
	request(){$.post('../public/ranking_average.php',data=>{
	this.setState({
		data
         });
		},'json');
	}
	componentDidMount(){
		this.request();
	}
	render(){
		var dataSource_=[];
		var dataSource=[];
		const data=this.state.data;
		var index=1;

		for(var i in data.rankList){
			if(data.rankList.hasOwnProperty(i)){
				if(data.nameList[i].length<=2||data.numdays[i]==0){
					continue;
				}
				else{
				dataSource_.push({
					// key:index,
					// ranking:index,
					name:data.nameList[i],
					// school:data.schoolList[i],
					steps:data.rankList[i],
					})
				}
			}
		}
		dataSource_.sort((o1,o2)=>o2.steps-o1.steps);
		for(var i=0;i<dataSource_.length;i++){
			dataSource.push({
				key:index,
				ranking:index,
				name:dataSource_[i].name,
				steps:dataSource_[i].steps,
			  
			  })
			index+=1;
			
		}


        // console.log(dataSource);

		// }

		return <div>
		<Table pagination={false} columns={columns1} dataSource={dataSource} size='middle' >
		</Table>
		</div>
	}
}
export default RankAverage
// <Table columns={column1} data={data1}>
// 		</Table>