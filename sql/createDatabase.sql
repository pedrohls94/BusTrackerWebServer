CREATE TABLE Line (Id SERIAL NOT NULL, Name varchar(255) NOT NULL, PRIMARY KEY (Id));
CREATE TABLE Bus (Id SERIAL NOT NULL, LineId BIGINT UNSIGNED NOT NULL, EposId BIGINT NOT NULL, PRIMARY KEY (Id));
CREATE TABLE Stop (Id SERIAL NOT NULL, Location varchar(255) NOT NULL, PRIMARY KEY (Id));
CREATE TABLE LineStop (LineId BIGINT UNSIGNED NOT NULL, StopId BIGINT UNSIGNED NOT NULL, PRIMARY KEY (LineId, StopId));

ALTER TABLE Bus ADD CONSTRAINT fk_Bus_LineId FOREIGN KEY (LineId) REFERENCES Line(Id);
ALTER TABLE LineStop ADD CONSTRAINT fk_LineStop_LineId FOREIGN KEY (LineId) REFERENCES Line(Id);
ALTER TABLE LineStop ADD CONSTRAINT fk_LineStop_StopId FOREIGN KEY (StopId) REFERENCES Stop(Id);