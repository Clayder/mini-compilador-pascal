const
    n=10;
var
    i : integer;
begin
    for i := 1 to n do
    var
        j,a : integer;
    begin
        a:=1;
        for j:=1 to i do
        begin
            a:=a*j;
        end;
        if a > 8 then
        begin
            print(a);
        end;
    end;
end.
