/* $Revision$ */
/*
 *   This file is part of PhpCompta.
 *
 *   PhpCompta is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   PhpCompta is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with PhpCompta; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
drop sequence s_isup;
drop sequence s_fdef;
drop sequence s_idef;

drop table isupp;
drop table fiche ;
drop table isupp_def ;
drop table fichedef ;
create sequence s_isup;

create sequence s_fdef;
create sequence s_idef;

create table fichedef (
       fd_id integer not null default nextval('s_fdef') primary key,
       fd_label text not null,
       fd_class_base text 
);
create table fiche (
       f_id integer not null references tmp_pcmn(pcm_val) primary key,
       f_label text not null,
       f_fd_id integer
);
create table isupp_def (
       isd_id	       integer not null default nextval('s_idef') primary key,
       isd_label       text,
       isd_fd_id       integer references fichedef(fd_id),
       isd_form	       bool
);

create table isupp (
       is_id	  integer not null default nextval('s_isup') primary key,
       is_f_id	  integer references tmp_pcmn(pcm_val),
       is_isd_id  integer references isupp_def(isd_id),
       is_value	  text
);
