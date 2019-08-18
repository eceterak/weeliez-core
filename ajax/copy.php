<?php

	if(isset($_POST['bikez']) || isset($_POST['motorcyclespecs'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$response = ['success' => true];
		$data = getPostValues();
		if($data->bikez) {
			$response['bikez'] = utf8_encode(file_get_contents($data->bikez));
		}
		if($data->motorcyclespecs) {
			$response['motorcyclespecs'] = utf8_encode(file_get_contents($data->motorcyclespecs));
		}
		echo json_encode($response);
	}

	if(isset($_POST['data']) && isset($_POST['bike_id'])) {
		require_once('../inc.php');
		$db = new mysqlib();
		$bike_id = $_POST['bike_id'];
		$data = json_decode($_POST['data']);
		$at = $db->select('attribute', 'attribute_name, attribute_id');
		$attributes = [];
		$motorcyclespecs = [];
		$bikez = [];
		foreach($at->fetch_data() as $k) {
			$attributes[$k->attribute_id] = strtolower($k->attribute_name);
		}
		foreach($data as $site => $specs) {
			switch($site) {
				case 'bikez':
					foreach($specs as $attribute => $value) {
						$value = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%:&-\/]/s', '', $value);
						switch($attribute) {
							case 'engine details':
								if(isset($bikez['engine type'])) {
									$bikez['engine type'] .= (substr($bikez['engine type'], -1) != '.') ? '. '.$value : ' '.$value;
								}
								else {
									$bikez['engine type'] = $value;
								}
							break;
							case 'displacement':
								$bikez[$attribute] = number_format(str_replace('mm', '', explode(' ', $value)[0]), 1, '.', '');
							break;
							case 'compression':
								$bikez['compression ratio'] = $value;
							break;
							case 'power':
								$hp = explode(' ', strtolower($value));
								$bikez['horse power'] = ['value' => '', 'sub' => ''];
								if(array_search('hp', $hp) !== false) {
									$bikez['horse power']['value'] = number_format($hp[array_search('hp', $hp) - 1], 1, '.', '');
								}
								if(array_search('rpm', $hp) !== false) {
									$bikez['horse power']['sub'] = $hp[array_search('rpm', $hp) - 1];
								}
							break;
							case 'torque':
								$torque = explode(' ', strtolower($value));
								$bikez['torque'] = ['value' => '', 'sub' => ''];
								if(array_search('nm', $torque) !== false) {
									$bikez['torque']['value'] = number_format($torque[array_search('nm', $torque) - 1], 1, '.', '');
								}
								if(array_search('rpm', $torque) !== false) {
									$bikez['torque']['sub'] = $torque[array_search('rpm', $torque) - 1];
								}
							break;
							case 'bore x stroke':
								$bikez[$attribute] = substr($value, 0, strpos($value, 'mm'));
							break;
							case 'frame type':
								$bikez['frame'] = $value;
							break;
							case 'rake (fork angle)':
								$bikez['rake'] = $value;
							break;
							case 'transmission type,final drive':
								$bikez['final drive'] = $value;
							break;
							case 'weight incl. oil, gas, etc':
								$bikez['wet weight'] = explode(' ', $value)[0];
							break;
							case 'overall length':
								$bikez['length'] = explode(' ', $value)[0];
							break;
							case 'overall height':
								$bikez['height'] = explode(' ', $value)[0];
							break;
							case 'overall width':
								$bikez['width'] = explode(' ', $value)[0];
							break;
							case 'seat height':
							case 'dry weight':
							case 'trail':
							case 'front wheel travel':
							case 'rear wheel travel':
							case 'front brakes diameter':
							case 'rear brakes diameter':
							case 'ground clearance':
							case 'wheelbase':
							case 'fuel capacity':
							case 'oil capacity':
							case 'fuel consumption':
							case 'top speed':
								$bikez[$attribute] = str_replace('mm', '', explode(' ', $value)[0]);
							break;
							default:
								$bikez[$attribute] = $value;
							break;
						}
					}
				break;
				case 'motorcyclespecs':
					foreach($specs as $attribute => $value) {
						$value = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%:&-\/]/s', '', $value);
						switch($attribute) {
							case 'max power':
								$hp = explode(' ', strtolower($value));
								$motorcyclespecs['horse power'] = ['value' => '', 'sub' => ''];
								if(array_search('ps', $hp) !== false) {
									$motorcyclespecs['horse power']['value'] = number_format($hp[array_search('ps', $hp) - 1], 1, '.', '');
								}
								if(array_search('hp', $hp) !== false) {
									$motorcyclespecs['horse power']['value'] = number_format($hp[array_search('hp', $hp) - 1], 1, '.', '');
								}
								if(array_search('rpm', $hp) !== false) {
									$motorcyclespecs['horse power']['sub'] = $hp[array_search('rpm', $hp) - 1];
								}
							break;
							case 'max torque':
								$torque = explode(' ', strtolower($value));
								$motorcyclespecs['torque'] = ['value' => '', 'sub' => ''];
								if(array_search('nm', $torque) !== false) {
									$motorcyclespecs['torque']['value'] = number_format($torque[array_search('nm', $torque) - 1], 1, '.', '');
								}
								if(array_search('rpm', $torque) !== false) {
									$motorcyclespecs['torque']['sub'] = $torque[array_search('rpm', $torque) - 1];
								}
							break;
							case 'capacity':
								$motorcyclespecs['displacement'] = number_format(explode(' ', $value)[0], 1, '.', '');
							break;
							case 'wet weight':
								$motorcyclespecs['wet weight'] = number_format(explode(' ', $value)[0], 1, '.', '');
							break;
							case 'engine':
								$motorcyclespecs['engine type'] = $value;
							break;
							case 'bore x stroke':
								$motorcyclespecs['bore x stroke'] = substr($value, 0, strpos($value, 'mm'));
							break;
							case 'lubrication':
								$motorcyclespecs['lubrication system'] = $value;
							break;
							case 'transmission':
								$motorcyclespecs['gearbox'] = $value;
							break;
							case 'exhaust':
								$motorcyclespecs['exhaust system'] = $value;
							break;
							case 'rake':
							case 'steering head angle':
								$motorcyclespecs[preg_replace('/\s+/', ' ', $attribute)] = str_replace('°', '', $value);
							break;
							case 'front brakes':
							case 'rear brakes':								
							break;
							case 'rims front':
								$motorcyclespecs['front rim'] = $value; 
							break;
							case 'rims rear':
								$motorcyclespecs['rear rim'] = $value; 
							break;
							case 'starting':
								$motorcyclespecs['starter'] = $value;
							break;
							case 'consumption average':
								$motorcyclespecs['fuel consumption'] = explode(' ', $value)[0];
							break;
							case 'engine management':
								$motorcyclespecs['electronic rider aids'] = $value;
							break;
							case 'induction':
								$motorcyclespecs['fuel system'] = $value;
							break;
							case 'dimensions':
								$value = str_replace('in', '', $value);
								$dimensions = explode(' ', strtolower($value));
								if(array_search('length', $dimensions) !== false) {
									$motorcyclespecs['length'] = number_format($dimensions[array_search('length', $dimensions) + 1]);			
								}
								if(array_search('width', $dimensions) !== false) {
									$motorcyclespecs['width'] = number_format($dimensions[array_search('width', $dimensions) + 1]);	
								}
								if(array_search('height', $dimensions) !== false) {
									$motorcyclespecs['height'] = number_format($dimensions[array_search('height', $dimensions) + 1]);
								}
							break;
							case 'wheelbase':
								$motorcyclespecs['wheelbase'] = number_format(str_replace('mm', '', explode(' ', $value)[0]));
							break;
							case 'fuel capacity':
								$motorcyclespecs['fuel capacity'] = number_format(explode(' ', $value)[0], 1, '.', '');
							break;
							case 'seat height':
							case 'dry weight':
							case 'trail':
							case 'front wheel travel':
							case 'rear wheel travel':
							case 'ground clearance':
							case 'wet weight':
							case 'top speed':
							case 'trail':
								$motorcyclespecs[preg_replace('/\s+/', ' ', $attribute)] = str_replace('mm', '', explode(' ', $value)[0]);
							break;
							default:
								$motorcyclespecs[preg_replace('/\s+/', ' ', $attribute)] = $value;
							break;
						}
					}
				break;
			}
		}
		$bikez_keys = array_keys($bikez);
		$motorcyclespecs_keys = array_keys($motorcyclespecs);
		$keys = array_merge(array_keys($bikez), array_keys($motorcyclespecs));
		$all = [];
		foreach($keys as $key) {
			if($id = array_search(strtolower($key), $attributes)) {
				if(isset($motorcyclespecs[$key]) && isset($bikez[$key])) {
					if(rand(0, 1) == 0) {
						$all[$key] = $motorcyclespecs[$key];
					}
					else {
						$all[$key] = $bikez[$key];
					}
				}
				elseif(isset($bikez[$key])) {
					$all[$key] = $bikez[$key];
				}
				else {
					$all[$key] = $motorcyclespecs[$key];
				}
			}
		}
		$db->where('bike_id', $bike_id);
		$db->delete('spec');
		foreach($all as $attr => $val) {
			if($id = array_search(strtolower($attr), $attributes)) {
				if(is_array($val)) {
					if($val['sub'] !== '') {
						$db->values($bike_id, $id, $val['value'], $val['sub']);
					}
					else {
						$db->values($bike_id, $id, $val['value'], NULL);
					}
				}
				else {
					$db->values($bike_id, $id, $val, NULL);	
				}
			}
		}
		//$db->debugMode();
		$result = $db->insert('spec', 'bike_id, attribute_id, spec_value, spec_sub');
		if($result) {
			echo json_encode(true);
		}
	}
?>