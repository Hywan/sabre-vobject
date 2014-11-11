%token  crlf                        \r\n
%token  begin                       BEGIN:
%token  end                         END:
%token  vcalendar                   VCALENDAR

%token  x_name                      X-([\w\d]{3}-)?[\w\d\-]+
%token  iana                        [\w\d\-]+
%token  semi_colon                  ;

%token  colon                       :                         -> value
%token  value:value                 [^\r\n]+
%token  value:crlf                  \r\n                      -> default

%token  equal                       =                         -> pvalue
// FIX ME
%token  pvalue:safe_char            [^";:,]+
%token  pvalue:double_quote         "                         -> pvalue_string
// FIX ME
%token  pvalue_string:qsafe_char    [^"]+
%token  pvalue_string:double_quote  "                         -> pvalue
%token  pvalue:comma                ,
%token  pvalue:colon                :                         -> value
%token  pvalue:semi_colon           ;                         -> default


#icalendar:
    ::begin:: ::vcalendar:: ::crlf::
    icalendar_body()
    ::end:: ::vcalendar:: ::crlf::

icalendar_body:
    icalendar_properties()
    component()+

icalendar_properties:
    properties()

#component:
    ::begin:: ( <iana[0]> | <x_name[0]> ) ::crlf::
    ( properties() | component() )*
    ::end:: ( ::iana[0]:: | ::x_name[0]:: ) ::crlf::

properties:
    ( property() ::crlf:: )+

#property:
    property_name() ( ::semi_colon:: property_parameter() )*
    ::colon:: property_value()

property_name:
    <iana> | <x_name>

#property_parameter:
    property_parameter_name()
      ::equal:: property_parameter_value()
    ( ::comma:: property_parameter_value() )*

property_parameter_name:
    <iana> | <x_name>

property_parameter_value:
    <safe_char> | quoted_string()

#property_value:
    <value>

#quoted_string:
    ::double_quote:: <qsafe_char> ::double_quote::
