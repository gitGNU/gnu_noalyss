
/*
 *   This file is part of WCOMPTA.
 *
 *   WCOMPTA is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   WCOMPTA is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with WCOMPTA; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

create sequence s_centralized;


CREATE TABLE centralized (
    c_id integer default nextval('s_centralized') not null primary key,
    c_j_id integer ,
    c_date date not null,
    c_internal text not null,
    c_montant float not null,
    c_debit boolean default 't',
    c_jrn_def int4 not null references jrn_def(jrn_def_id),
    c_poste integer references tmp_pcmn(pcm_val),
    c_description text,
    c_grp integer not null,
    c_comment text,
    c_rapt text,
    c_periode integer
);


