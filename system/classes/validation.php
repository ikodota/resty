<?php
class Validation extends Resty_Validation {
	
	public function getErrors()
	{
		$messages = array();

		foreach ($this->_errors as $field => $set)
		{
			list($error, $params) = $set;		
			$label = $this->_labels[$field];

			$values = array(':field' => $label);

			if ($params)
			{
				//$values[':value'] = array_shift($params);//注释掉这行
				//输出错误信息的时候，可显示带有多个参数的验证提示。比如range能在显示为(10,20)，而不是10.
				//在message.php中 配置不变如：'range' => ':field不在范围内(:value)',
				foreach ($params as $key => $v) {
					$values[':value'][$key]=$params[$key];
				}

				if (is_array($values[':value']))
				{
					$values[':value'] = implode(', ', Arr::flatten($values[':value']));

				}

				foreach ($params as $key => $value)
				{
					if (is_array($value))
					{
						$value = implode(', ', Arr::flatten($value));
					}

					if (isset($this->_labels[$value]))
					{
						$value = $this->_labels[$value];

					}

					$values[':param'.($key + 1)] = $value;
				}
			}
			else
			{
				$values[':value'] = NULL;
			}

			$resource = Request::instance()->get_resource();

			if ($message = Config::get("message.{$resource}.{$field}.{$error}"))
			{
			}
			elseif ($message = Config::get("message.{$field}.{$error}"))
			{
			}
			elseif ($message = Config::get("message.{$field}.default"))
			{
			}
			elseif ($message = Config::get("message.{$error}"))
			{
			}
			else
			{
				$message = "{$field}.{$error}";
			}

			$message = strtr($message, $values);
			$messages[$field] = $message;
		}

		return $messages;
	}

}
