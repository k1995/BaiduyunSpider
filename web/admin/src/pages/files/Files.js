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
	DialogTitle,
  CircularProgress
} from "@material-ui/core";
import PageTitle from '../../components/PageTitle';
import { fetchFiles, pushUrl } from './FileState';


class Files extends React.Component {

	state = {
		open: false,
		page: 1,
		pageSize: 10,
	};

	switchDialogOpen = () => {
		this.setState({
				open: !this.state.open
			})
	};

	refetch = () => {
		if(this.state.open) {
			// Dialog打开的时候不刷新，防止窗口抖动
			return;
		}
		this.props.dispatch(fetchFiles(this.state.page + 1, this.state.pageSize));
	};

	submitUrl = (e) => {
    e.preventDefault();
    const { dispatch } = this.props;
    dispatch(pushUrl(e.target['url'].value))
			.then((err) => {
				if(err) {
          alert(err)
        }
    	});
		this.switchDialogOpen();
	};

	componentDidMount() {
		this.refetch();
		this.timer = setInterval(this.refetch, 5000);
	}

	componentWillUnmount() {
		clearInterval(this.timer);
	}

	render() {
		const { files, task, total } = this.props['files'];
		const data = files.map(file => [
			[file['url'], file['server_filename']],
			format(file['ctime'] * 1000, 'zh_CN'),
			prettyBytes(file['size']),
			file['_id'],
			file['last_updated']]
		);

		const columns = [
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
      "更新时间"
		];

		const options = {
      count: total,
      serverSide: true,
			page: this.state.page,
			rowsPerPage: this.state.pageSize,
      onTableChange: (action, tableState) => {
        this.setState({
					page: tableState.page,
					pageSize: tableState.rowsPerPage
				});
        setTimeout(() => this.refetch(), 0);
      }
    };

    const AddTask = () => (
      <Dialog open={this.state.open || task.pushing}
							onClose={this.switchDialogOpen}>
        <DialogTitle>新增采集任务</DialogTitle>
        <DialogContent>
          <DialogContentText>
            请输入分享文件的访问地址。例如：<br/>
            https://pan.baidu.com/s/17BtXyO-i02gsC7h4QsKexg
          </DialogContentText>
          <form id="form" onSubmit={this.submitUrl}>
            <TextField
							name="url"
              autoFocus
              margin="dense"
              label="URL"
              type="url"
              required
              fullWidth
            />
          </form>
        </DialogContent>
        <DialogActions>
					{task.pushing ? (
							<CircularProgress size={26} />
						): (
							<Button onClick={this.switchDialogOpen} color="primary">
            取消
          </Button>)}

          <Button form="form" type="submit" color="primary">
            提交
          </Button>
        </DialogActions>
      </Dialog>
		);

		return (
			<React.Fragment>
				<PageTitle title="分享的文件" button="新增" onClick={this.switchDialogOpen} />
				<AddTask />
				<Grid container spacing={32}>
					<Grid item xs={12}>
						<MUIDataTable
							title="文件列表"
							data={data}
							columns={columns}
							options={options}
						/>
					</Grid>
				</Grid>
			</React.Fragment>)
	}
}


const mapStateToProps = state => state;
export default connect(mapStateToProps)(Files);