import React from 'react';
import { Grid } from '@material-ui/core';
import MUIDataTable from "mui-datatables";
import { connect } from 'react-redux';
import prettyBytes from 'pretty-bytes';
import { format } from 'timeago.js';
import {
	Button,
	TextField,
	Dialog,
	DialogActions,
	DialogContent,
	DialogContentText,
	DialogTitle
} from "@material-ui/core";
import PageTitle from '../../components/PageTitle';
import { fetchFiles } from './FileState';


class Files extends React.Component {

	state = {
		open: false
	};

	switchDialogOpen = () => {
		this.setState({
				open: !this.state.open
			})
	};

	componentDidMount() {
		const { dispatch } = this.props;
		dispatch(fetchFiles());
	}

	render() {
		const { files } = this.props['files'];
		let data = files.map(file => [
			[file['url'], file['server_filename']],
			format(file['ctime'] * 1000, 'zh_CN'),
			prettyBytes(file['size']),
			file['_id'],
			file['last_updated']]
		);

		return (
			<React.Fragment>
				<PageTitle title="分享的文件" button="新增" onClick={this.switchDialogOpen} />
				<Dialog open={this.state.open} onClose={this.switchDialogOpen} aria-labelledby="form-dialog-title">
					<DialogTitle id="form-dialog-title">新增采集任务</DialogTitle>
					<DialogContent>
						<DialogContentText>
							请输入要采集的分享文件的访问地址。<br/>
							例如：https://pan.baidu.com/s/17BtXyO-i02gsC7h4QsKexg
						</DialogContentText>
						<TextField
							autoFocus
							margin="dense"
							id="url"
							label="URL"
							type="url"
							fullWidth
						/>
					</DialogContent>
					<DialogActions>
						<Button onClick={this.switchDialogOpen} color="primary">
							取消
						</Button>
						<Button onClick={this.switchDialogOpen} color="primary">
							提交
						</Button>
					</DialogActions>
				</Dialog>
				<Grid container spacing={32}>
					<Grid item xs={12}>
						<MUIDataTable
							title="文件列表"
							data={data}
							columns={[
								{
									name: "文件名",
									options: {
										customBodyRender: (value, tableMeta, updateValue) => {
											return (
												<a href={value[0]}>{value[1]}</a>
											);
										},
									}
								},
								"分享时间",
								"大小",
								"ID",
								"更新时间"]}
							options={{
								filterType: 'checkbox',
							}}
						/>
					</Grid>
				</Grid>
			</React.Fragment>)
	}
}


const mapStateToProps = state => state;
export default connect(mapStateToProps)(Files);