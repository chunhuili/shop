<?php

return [
    'alipay' => [
        'app_id' => '2016092700610058',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA7Bk/s4kZpUp6hdRvN/9UrubZTpQzBxJlhzfNDGGCOGMbo6kh/hYlwesiE2AkPJIBkGCInErYAQ0/wZdXRdL2RDxy/6npMQZfLhqwtV082lqlaIcc1uvUlLW5vmiyW6SIMx0t50ynPqPGxkfmgLhNWDSPh92pe7ZstCcyCe/XBvVsuBl4f0poAzwkuz9B7dCBJf6bWcbPhqpBrkvEKeZxGBeY8alONVFGAQJElpJQDjZ5jl+D8OLtr5S2C3DQmLn8QwbHXNo/qW1C27YASVtjzoWX3Ofi9PwRCecCUUDWBs4pM4AzuuvIzgHb7/NI/Cnyt4abpuPpcLzkIAiTnxsUoQIDAQAB',
        'private_key' => 'MIIEowIBAAKCAQEAyECqk1Ko6avIw3p6x4Nvc9RG9Un65Ic0Q15pPwUeCIVelkEUulgdALpFMg7lyEjHV2HIe7vuDiiY6zGMOQOw4ClgBHSNZMJCde1oV8mUK/1NPIYKz/X8TyVecSVhQ/XBKiPKa5jy8dGrh4vk2OudEbM1LJaLp2a4aSQkzdmxSk7zk8vDkVDnE1mIqxfBAmPEeUWxtp0aNLuETkADaUF+5XIuuxdImXmejvVaA4DuarncgKYfwCnBuc8K22PrXdJnqEc9EovWnY0vWZeU9lEptedOye2RlJPanAZ5zbtAj/RZJRVUP+UPth0Cc4q9jHFK0/mjfBseApIbuDdFSNe/CQIDAQABAoIBAQCWKm//Y/gIqmo1tE4H+RcFvASuAhkjcAQQFOdFe9fmueSRNiKODm9JUWV8QVGsnG7npwsJRocglO7/R7uIMa1cm4pGLQSur0HZXvtlAWOcjSlzHm1TP3pB+5rTrB1BMjP1Hru3bg2Yqws+7PfiduTlFgbk9cup78yQA6uZtgpN1oxEntYq1KJx7Nf9e0fNWjCIp/imbXJwPTiCTvwurZVr2vaqIG5AcxQ0pLXDx7HEPBlaJsBAspK1OpkE6EFsFNBWLm+/LJen2jtChv7QVYncZtzyXkmTziua+ACjkRh5R0iKHlYrXc9WEsSuleNHCD/T8TLfOAlxhyaKws/ut0X1AoGBAPLr42DbGlGVBKkqGHErAr3vdmxwv5Tg+BbeINmXHeHzHEZomrtmX4b6eKct+rniR3eEIHEJ1GT/pxLkk3FQtvUkuYPw0qiFhPiGZTH6oWhWUquchNn521K8gqvjiW1yGwCLATe7mpowDlqt5uJM1bOYYt6UkQtxbUPHVPJnTvxvAoGBANMIr8ZDnquWZAgrYSmyeludHfe71waaOnNyTUAuEfGA1UGa9GZl2NWpHuWJYtEuQJ5jnU2ziCi0hgW6pJVYUd/w4gL3mdvYcLZ8LDT4PH4+i80OoJ22/JB4+ewT3l1aY+CP1TyB2mXVpBWL1lKau4tAOuci5bEGKvwSUbyvWqgHAoGAOR6CPpoGXDx/9g7x5FpKsMKHxAPW6HjlnOrqKn/ABQsXEO8zwvDVcXWfvR+wnti4GUA2KJ45FO6QKH8atEBYZNgXzxBnWDzdAVsInHHkVhZGJhgNnPuFOLUevLAp9lQoST5mMOap+atnFKdjbL7IWQxx4whn+prXKGLMdJsaFX0CgYAxfukTHEr7vaMqk1oPmI/AKwKbVxAguU2aRCXANs34kZny8DAAUETiH+9iMK3fl/SJ8Z00WzZFLcn9UABIEtXpKysnPQF2IbaV5lvL9KjjhzHs31BmjUOBcw5TA3n09T1VrwZ5UY9ysP/yGcEj0KzKBT+LdJDgyyyLwJQxKAOgQwKBgAWfHALo2sNXYu4BKU5yrYFLP1HDnnoGA2JQAVgS/Pnw7Mkr+c/iuF88IBItoOkQUt4dJODxUwl7MsbFG38VWZlhnW/GVtPxpzb97TBHrgbhty0vk4cdXRl2RD/Tew73NuByUsiUVHlFTzAX86Wr9L3RCr/0qKSXKI+4UwVuGul2',
        'log' => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id' => '',
        'mch_id' => '',
        'key' => '',
        'cert_client' => '',
        'cert_key' => '',
        'log' => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],


];