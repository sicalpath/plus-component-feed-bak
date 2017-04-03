/*
|--------------------------------------------------------
| 文档 webpack 配置文件
|--------------------------------------------------------
|
| 配置文件使用 ES6 语法配置，这样能保证整个文档项目的语法统一性
| 修改配置文件请使用 ES6 语法对 webpack 进行配置。
|
| @author Seven Du <shiweidu@outlook.com>
|
*/

import path from 'path';
import webpack from 'webpack';

/*
|--------------------------------------------------------
| 获取 NODE 环境变量模式
|--------------------------------------------------------
|
| 获取变量的用处用于判断当前运行环境是否属于正式编译使用。
|
*/
const NODE_ENV = process.env.NODE_ENV || 'development';

/*
|--------------------------------------------------------
| 获取是否是正式环境
|--------------------------------------------------------
|
| 定义该常量的用处便于程序中多处的判断，用于快捷判断条件。
|
*/
const isProd = NODE_ENV === 'production';

/*
|---------------------------------------------------------
| 源代码根
|---------------------------------------------------------
|
| 获取源代码所处的根路径
|
*/
const src = path.join(__dirname, 'resource');

/*
|---------------------------------------------------------
| 解决路径位置
|---------------------------------------------------------
|
| 解析并正确的返回已经存在的相对于根下的文件或者目录路径。
|
*/
const resolve = pathname => path.resolve(src, pathname);

/*
|---------------------------------------------------------
| 合并路径位置
|---------------------------------------------------------
|
| 合并得到相对于源根路径下的文件路径。
|
*/
const join = pathname => path.join(src, pathname);

const webpackConfig = {

/*
|---------------------------------------------------------
| 开发工具
|---------------------------------------------------------
|
| 判断是不是正式环境，非正式环境，加载 source-map
|
*/
devtool: isProd ? false : 'source-map',

/*
|---------------------------------------------------------
| 配置入口
|---------------------------------------------------------
|
| 入口配置，多个入口增加更多配置项。这里配置需要编译的资源入口。
|
*/
entry: {
  admin: resolve('main.js')
},

};

export default webpackConfig;
