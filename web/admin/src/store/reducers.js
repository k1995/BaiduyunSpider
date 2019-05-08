import { combineReducers } from 'redux';

import layout from '../components/Layout/LayoutState';
import login from '../pages/login/LoginState';
import users from '../pages/users/UserState';

export default combineReducers({
  layout,
  login,
  users
});