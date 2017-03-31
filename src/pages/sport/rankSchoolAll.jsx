import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import {Table} from 'antd'

const columns1=[{title:'名次',dataIndex:'ranking'},{title:'姓名',dataIndex:'name'},{title:'步数',dataIndex:'steps'}];

class RankSchoolAll extends React.Component{
	constructor(){
		super();
		this.state={
			rankList:[],
			schoolName:'',
		}
	}
	request(){$.post('../public/internal_ranking.php',data=>{
	this.setState({
		rankList:data.rankList,
		schoolName:data.schoolName,
         });
	// console.log(typeof this.state.rankList);
    },'json');

	}
	componentDidMount(){
		this.request();

	}

	render(){
		const data=[];
		const rankList=this.state.rankList;
		var index=1;
		for(let i in rankList){
			if(rankList.hasOwnProperty(i)){
				data.push({
				key:index,
				ranking:index,
				name:i,
				steps:rankList[i],
			})
				index+=1;
			}
		}


		// }

		return <div>
        {this.state.schoolName}总步数排名
		<Table pagination={false} columns={columns1} dataSource={data} size='middle' >
		</Table>
		</div>
	}
}
export default RankSchoolAll
// <Table columns={column1} data={data1}>
// 		</Table>