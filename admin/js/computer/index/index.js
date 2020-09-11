var INDEX = {
	init: function()
	{
		this.getInfo();
		this.start();
		this.net.init();
	},
	getInfo: function()
	{
		API.post(URI+'index/getSystemInfo', {}, function(res){
			INDEX.infoInit(res.data);
		});
	},
	infoInit: function(data)
	{
		if (!data || data.length == 0) return false;
		$('#info').text(data.os_name);
		$('#running').text(data.uptime);
		//系统负载
		var html = '';
		$('.loadbox .occupy').html(data.server_load+'%');
		this.loadcircle($('.loadbox'), data.server_load);
		var tmp = '';
		if (30 < data.server_load < 50) tmp = 'style="color:#fc6d26;"'
		if (data.server_load > 50) tmp = 'style="color:red;"'
		html = '<div '+tmp+'>'+data.server_loadtext+'</div>';
		$('.loadbox .text-box').html(html);
		//cpu
		$('.cpubox .occupy').html(data.load_avg+'%');
		this.loadcircle($('.cpubox'), data.load_avg);
		html = '<div >'+data.cpu_name+'</div>';
		html += '<div >'+data.cpu_num+' '+data.cpu_process+'</div>';
		$('.cpubox .text-box').html(html);
		//内存
		$('.membox .occupy').html(data.mem_percent+'%');
		this.loadcircle($('.membox'), data.mem_percent);
		html = '<div >总计: '+data.mem_total+' MB</div>';
		html += '<div >使用: '+data.mem_used+' MB</div>';
		html += '<div >剩余: '+data.mem_free+' MB</div>';
		$('.membox .text-box').html(html);
		//磁盘
		$('.diskbox .occupy').html(data.disk_percent+'%');
		this.loadcircle($('.diskbox'), data.disk_percent);
		html = '<div >总计: '+data.disk_total+'M</div>';
		html += '<div >使用: '+data.disk_used+'M</div>';
		html += '<div >剩余: '+data.disk_free+'M</div>';
		$('.diskbox .text-box').html(html);
		//刷新流量
        $("#upSpeed").html(data.net_up + ' KB');
        $("#downSpeed").html(data.net_down + ' KB');
        $("#downAll").html(data.net_uptotal+' MB');
        $("#upAll").html(data.net_downtotal+' MB');
        INDEX.net.add(data.net_up, data.net_down);
        if (INDEX.net.table) INDEX.net.table.setOption({ xAxis: { data: INDEX.net.data.aData }, series: [{ name: '上行', data: INDEX.net.data.uData }, { name: '下行', data: INDEX.net.data.dData }] });
	},
	start: function()
	{
		this.interval = null;
		this.interval = setInterval(function() { 
            INDEX.getInfo();
        }, 3000);
	},
	loadcircle: function(obj, num)
	{
		if (num > 50) {
			obj.find('.bar-right-an').css({'transform': 'rotate(45deg)', 'transition': 'transform 0s linear 0s'});
			var deg = -135+3.6*(num - 50);
			obj.find('.bar-left-an').css({'transform': 'rotate('+deg+'deg)'});
		} else {
			obj.find('.bar-left-an').attr('style', '');
			var deg = -135+3.6*num;
			obj.find('.bar-right-an').css({'transform': 'rotate('+deg+'deg)'});
		}
	},
	net: {
        table: null,
        data: {
            uData: [],
            dData: [],
            aData: []
        },
        init: function() {
            //流量图表
            INDEX.net.table = echarts.init(document.getElementById('NetImg'));
            var obj = {};
            obj.dataZoom = [];
            obj.unit = '单位:Kb/s';
            obj.tData = INDEX.net.data.aData;

            obj.list = [];
            obj.list.push({ name: '上行', data: INDEX.net.data.uData, circle: 'circle', itemStyle: { normal: { color: '#f7b851' } }, areaStyle: { normal: { color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{ offset: 0, color: 'rgba(255, 140, 0,0.5)' }, { offset: 1, color: 'rgba(255, 140, 0,0.8)' }], false) } }, lineStyle: { normal: { width: 1, color: '#aaa' } } });
            obj.list.push({ name: '下行', data: INDEX.net.data.dData, circle: 'circle', itemStyle: { normal: { color: '#52a9ff' } }, areaStyle: { normal: { color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{ offset: 0, color: 'rgba(30, 144, 255,0.5)' }, { offset: 1, color: 'rgba(30, 144, 255,0.8)' }], false) } }, lineStyle: { normal: { width: 1, color: '#aaa' } } });
            option = INDEX.format_option(obj)

            INDEX.net.table.setOption(option);
            window.addEventListener("resize", function() {
                INDEX.net.table.resize();
            });
        },
        add: function(up, down) {
            var _net = this;
            var limit = 8;
            var d = new Date()

            if (_net.data.uData.length >= limit) _net.data.uData.splice(0, 1);
            if (_net.data.dData.length >= limit) _net.data.dData.splice(0, 1);
            if (_net.data.aData.length >= limit) _net.data.aData.splice(0, 1);

            _net.data.uData.push(up);
            _net.data.dData.push(down);
            _net.data.aData.push(d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds());
        }
    },
    format_option: function(obj,type)
    {
		option = {
			tooltip: {
				trigger: 'axis',
				axisPointer: {
					type: 'cross'
				},
				formatter: obj.formatter
			},
			xAxis: {
				type: 'category',
				boundaryGap: false,
				data: obj.tData,
				axisLine:{
					lineStyle:{
						color:"#666"
					}
				}
			},
			yAxis: {
				type: 'value',
				name: obj.unit,
				boundaryGap: [0, '100%'],
				min:0,
				splitLine:{
					lineStyle:{
						color:"#ddd"
					}
				},
				axisLine:{
					lineStyle:{
						color:"#666"
					}
				}
			},
			dataZoom: [{
				type: 'inside',
				start: 0,
				zoomLock:true
			}, {
				start: 0,
				handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
				handleSize: '80%',
				handleStyle: {
					color: '#fff',
					shadowBlur: 3,
					shadowColor: 'rgba(0, 0, 0, 0.6)',
					shadowOffsetX: 2,
					shadowOffsetY: 2
				}
			}],
			series: []
		};		
		if(obj.legend) option.legend = obj.legend;		
		if(obj.dataZoom) option.dataZoom = obj.dataZoom;
		
		for (var i=0;i<obj.list.length;i++) 
		{
			var item = obj.list[i];
			series = {
				name : item.name,
				type : item.type?item.type:'line',
				smooth : item.smooth ? item.smooth : true,
				symbol : item.symbol ? item.symbol : 'none',
				showSymbol:item.showSymbol?item.showSymbol:false,
				sampling : item.sampling ? item.sampling : 'average',
				areaStyle : item.areaStyle ? item.areaStyle : {},
				lineStyle : item.lineStyle ? item.lineStyle : {},
				itemStyle : item.itemStyle ? item.itemStyle : { normal:{ color: 'rgb(0, 153, 238)'}},
				symbolSize:6,
				symbol: 'circle',
				data :  item.data						
			}
			option.series.push(series);
		}
		return option;
	}
};