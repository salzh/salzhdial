echo 'step 1:'
    echo "alter table t_work_detail modify column TimeLength int(4) default 0" | mysql evoice
    echo "alter table t_work_detail modify column Money double default 0" | mysql evoice
    echo "alter table t_user modify column voiceMoney double default 0" | mysql evoice

    echo "alter table t_work modify column SendTime datetime " | mysql evoice
    echo "alter table t_work modify column FixedTime datetime " | mysql evoice
    echo "alter table t_work modify column EndTime datetime " | mysql evoice
    echo "alter table t_work_detail modify column SendTime datetime " | mysql evoice




echo 'Test:'
    echo "truncate t_work" | mysql evoice
    echo "truncate t_work_detail" | mysql evoice
    echo "insert into t_work(Id,UserId,SendTimeType,VoiceFile,IfClick) values (1,1,0,'dispatch/10000',0)" | mysql evoice
    
    echo "update t_work_detail set SendTime='' where Id=4; update t_work set WorkState=0 where Id=11" | mysql evoice